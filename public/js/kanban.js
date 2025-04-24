// public/js/kanban.js
document.addEventListener('DOMContentLoaded', () => {
  // helper CSRF token
  const csrf = () => document.querySelector('meta[name="csrf-token"]').getAttribute('content');

  // current Kanban
  let currentKanbanId = null;
  let currentKanbanType = null;

  // ambil ID dari hidden input bila page di-kanban/{id}
  const hiddenId = document.getElementById('kanban_id');
  if (hiddenId && hiddenId.value) {
    currentKanbanId = hiddenId.value;
  }

  const detailContainer = document.getElementById('kanban-detail');
  const kanbanListEl   = document.getElementById('kanban-list');

  // fungsi reload kolom & card
  function loadColumns(id) {
    if (!detailContainer || !id) return;
    fetch(`/kanban/${id}/columns`)
      .then(r => r.text())
      .then(html => detailContainer.innerHTML = html)
      .catch(console.error);
  }

  // Sidebar: pilih Kanban
  kanbanListEl?.addEventListener('click', e => {
    const li = e.target.closest('li.kanban-item');
    if (!li) return;
    currentKanbanId   = li.dataset.id;
    currentKanbanType = li.dataset.type || null;
    hiddenId && (hiddenId.value = currentKanbanId);
    loadColumns(currentKanbanId);
  });

  // DELEGASI SEMUA EVENT
  document.body.addEventListener('click', e => {
    // ========== EDIT KANBAN (show modal) ==========
    if (e.target.matches('.edit-kanban')) {
      const id    = e.target.dataset.id;
      const title = e.target.dataset.title;
      document.getElementById('edit_kanban_id').value    = id;
      document.getElementById('edit_kanban_title').value = title;
      bootstrap.Modal.getOrCreateInstance(
        document.getElementById('editKanbanModal')
      ).show();
    }

    // ========== DELETE KANBAN ==========
    if (e.target.matches('.delete-kanban')) {
      e.stopPropagation();
      if (!confirm('Yakin ingin menghapus kanban ini?')) return;
      const id = e.target.dataset.id;
      fetch(`/kanban/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrf() }
      })
      .then(r => r.json())
      .then(json => {
        if (json.success) {
          // hapus di sidebar
          e.target.closest('li.kanban-item').remove();
          // kosong detail jika itu yang aktif
          if (currentKanbanId === id && detailContainer) {
            detailContainer.innerHTML = '';
          }
        }
      });
    }

    // ========== SHOW CREATE CARD MODAL & DYNAMIC FIELDS ==========
    if (e.target.matches('.create-card')) {
      const colId = e.target.dataset.id;
      document.getElementById('column_id').value = colId;
      const cv = document.getElementById('cardValues');
      cv.innerHTML = '';
      if (currentKanbanType === 'sales') {
        cv.insertAdjacentHTML('beforeend', `
          <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="hidden" name="key[]" value="price">
            <input type="number" name="value[]" class="form-control" placeholder="Isi harga">
          </div>`);
      }
      cv.insertAdjacentHTML('beforeend', `
        <div class="mb-3">
          <label class="form-label">Field</label>
          <input type="text" name="key[]" class="form-control mb-2" placeholder="Nama Field" required>
          <input type="text" name="value[]" class="form-control" placeholder="Isi Field" required>
        </div>`);
    }

    // ========== DELETE COLUMN ==========
    if (e.target.matches('.delete-column')) {
      if (!confirm('Yakin ingin menghapus column ini?')) return;
      const colId = e.target.dataset.id;
      fetch(`/kanban-column/${colId}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrf() }
      })
      .then(r => r.json())
      .then(json => {
        if (json.success) loadColumns(currentKanbanId);
      });
    }

    // ========== SHOW EDIT COLUMN MODAL ==========
    if (e.target.matches('.edit-column')) {
      const id   = e.target.dataset.id;
      const name = e.target.dataset.name;
      document.getElementById('edit_column_id').value   = id;
      document.getElementById('edit_column_name').value = name;
      bootstrap.Modal.getOrCreateInstance(
        document.getElementById('editColumnModal')
      ).show();
    }

    // ========== MOVE CARD ==========
    const moveLink = e.target.closest('.move-card');
    if (moveLink) {
      e.preventDefault();
      if (!confirm('Pindahkan card ke kolom ini?')) return;
    
      // Ambil data dari atribut data-*
      const cardId   = moveLink.getAttribute('data-card-id');
      const newColId = moveLink.getAttribute('data-column-id');
    
      // Siapkan FormData
      const form = new FormData();
      form.append('_token', csrf());
      form.append('column_id', newColId);
    
      fetch(`/kanban-card/${cardId}/move`, {
        method: 'POST',
        body: form
      })
      .then(async res => {
        if (!res.ok) {
          // Jika error (500, dll), tampilkan HTML error di console
          const text = await res.text();
          console.error('Error move card:', text);
        } else {
          const json = await res.json();
          if (!json.success) {
            console.warn('Move card gagal:', json);
          }
        }
        // Selalu reload columns untuk auto‐update UI
        loadColumns(currentKanbanId);
      })
      .catch(err => {
        console.error('Fetch error move card:', err);
        loadColumns(currentKanbanId);
      });
    }


    // ========== DELETE CARD ==========
    if (e.target.matches('.delete-card')) {
      if (!confirm('Yakin ingin menghapus card ini?')) return;
      const id = e.target.dataset.id;
      fetch(`/kanban-card/${id}`, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': csrf() }
      })
      .then(r => r.json())
      .then(json => {
        if (json.success) loadColumns(currentKanbanId);
      });
    }

    // ========== EDIT CARD (show modal) ==========
    if (e.target.matches('.edit-card')) {
      const cardId = e.target.dataset.id;
      fetch(`/kanban-card/${cardId}`)
        .then(r => r.json())
        .then(card => {
          const form = document.getElementById('editCardForm');
          form.card_id.value     = card.id;
          form.title.value       = card.title;
          form.description.value = card.description || '';
          const ctr = document.getElementById('edit_card_values_container');
          ctr.innerHTML = '';
          card.values.forEach(v => {
            ctr.insertAdjacentHTML('beforeend', `
              <div class="mb-3">
                <label class="form-label">${v.key}</label>
                <input type="text" name="key[]" class="form-control mb-2" value="${v.key}" required>
                <input type="text" name="value[]" class="form-control" value="${v.value}" required>
                <button type="button" class="btn btn-danger btn-sm remove-field mt-2">Hapus</button>
              </div>`);
          });
          bootstrap.Modal.getOrCreateInstance(
            document.getElementById('editCardModal')
          ).show();
        });
    }

    // ========== ADD / REMOVE DYNAMIC FIELD ==========
    if (e.target.matches('#addCardValue')) {
      document.getElementById('cardValues').insertAdjacentHTML('beforeend', `
        <div class="mb-3">
          <label class="form-label">Field</label>
          <input type="text" name="key[]" class="form-control mb-2" placeholder="Nama Field" required>
          <input type="text" name="value[]" class="form-control" placeholder="Isi Field" required>
          <button type="button" class="btn btn-danger btn-sm remove-field mt-2">Hapus</button>
        </div>`);
    }
    if (e.target.matches('.remove-field')) {
      e.target.closest('.mb-3').remove();
    }

    // ========== HISTORY CARD ==========
    if (e.target.matches('.history-card')) {
      const id = e.target.dataset.cardId;
      fetch(`/kanban-card/${id}/history`)
        .then(r => r.text())
        .then(html => {
          document.getElementById('cardHistoryContent').innerHTML = html;
          bootstrap.Modal.getOrCreateInstance(
            document.getElementById('cardHistoryModal')
          ).show();
        });
    }

    // ========== INVITE FORM TOGGLE ==========
    if (e.target.matches('#invite_by_id, #invite_by_email')) {
      const byId    = document.getElementById('invite_by_id').checked;
      document.getElementById('inviteUserIdDiv')
        .classList.toggle('d-none', !byId);
      document.getElementById('inviteEmailDiv')
        .classList.toggle('d-none', byId);
    }
  });


  // ========== FORM SUBMITS ==========

  // 1) Create Kanban
  document.getElementById('createKanbanForm')?.addEventListener('submit', e => {
    e.preventDefault();
    const form = e.target;
    fetch('/kanban', {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrf() },
      body: new FormData(form)
    })
    .then(r => r.json())
    .then(json => {
      if (json.success) {
        const li = document.createElement('li');
        li.className = 'list-group-item kanban-item';
        li.dataset.id   = json.kanban.id;
        li.dataset.type = json.kanban.type;
        li.innerHTML = `
          <a href="#" class="flex-grow-1 text-decoration-none">${json.kanban.title}</a>
          <button class="btn btn-warning btn-sm float-end ms-1 edit-kanban"
                  data-id="${json.kanban.id}"
                  data-title="${json.kanban.title}">Edit</button>
          <button class="btn btn-danger btn-sm float-end delete-kanban"
                  data-id="${json.kanban.id}">Hapus</button>
        `;
        kanbanListEl.appendChild(li);
        bootstrap.Modal.getInstance(form.closest('.modal')).hide();
      }
    });
  });

  // 2) Edit Kanban
  document.getElementById('editKanbanForm')?.addEventListener('submit', e => {
    e.preventDefault();
    const form = e.target;
    const data = new FormData(form);
    fetch(`/kanban/${data.get('kanban_id')}`, {
      method: 'POST',       // override PUT via hidden _method
      headers: { 'X-CSRF-TOKEN': csrf() },
      body: data
    })
    .then(r => r.json())
    .then(json => {
      if (json.success) {
        // update title sidebar
        const li = kanbanListEl.querySelector(`li.kanban-item[data-id="${json.kanban.id}"]`);
        li.querySelector('a').textContent = json.kanban.title;
        bootstrap.Modal.getInstance(form.closest('.modal')).hide();
      }
    });
  });

  // 3) Create Column
  document.getElementById('createColumnForm')?.addEventListener('submit', e => {
    e.preventDefault();
    const form = e.target;
    const data = new FormData(form);
    fetch(`/kanban/${currentKanbanId}/columns`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrf() },
      body: data
    })
    .then(r => r.json())
    .then(json => {
      if (json.success) {
        bootstrap.Modal.getInstance(form.closest('.modal')).hide();
        loadColumns(currentKanbanId);
      }
    });
  });

  // 4) Edit Column
  document.getElementById('editColumnForm')?.addEventListener('submit', e => {
    e.preventDefault();
    const form = e.target;
    const data = new FormData(form);
    fetch(`/kanban-column/${data.get('column_id')}`, {
      method: 'POST',  // override PUT
      headers: { 'X-CSRF-TOKEN': csrf() },
      body: data
    })
    .then(r => r.json())
    .then(json => {
      if (json.success) {
        bootstrap.Modal.getInstance(form.closest('.modal')).hide();
        loadColumns(currentKanbanId);
      }
    });
  });

  // 5) Create Card
  document.getElementById('createCardForm')?.addEventListener('submit', e => {
    e.preventDefault();
    const form = e.target;
    fetch(`/kanban-column/${form.column_id.value}/cards`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrf() },
      body: new FormData(form)
    })
    .then(r => r.json())
    .then(json => {
      if (json.success) {
        bootstrap.Modal.getInstance(form.closest('.modal')).hide();
        loadColumns(currentKanbanId);
      }
    });
  });

  // 6) Edit Card
  // 6) Edit Card
document.getElementById('editCardForm')?.addEventListener('submit', e => {
  e.preventDefault();
  const form = e.target;
  const data = new FormData(form);
  fetch(`/kanban-card/${data.get('card_id')}`, {
    method: 'POST',
    headers: { 'X-CSRF-TOKEN': csrf() },
    body: data
  })
  .then(async r => {
    if (!r.ok) {
      // Kalau gagal (500, 422, dsb), log HTML error-nya
      console.error('Error edit card:', await r.text());
    } else {
      const json = await r.json();
      if (json.success) {
        bootstrap.Modal.getInstance(form.closest('.modal')).hide();
      }
    }
    // apapun hasilnya, refresh tampilan
    loadColumns(currentKanbanId);
  })
  .catch(err => {
    console.error('Fetch error edit card:', err);
    loadColumns(currentKanbanId);
  });
});


  // 7) Invite Form
  document.getElementById('inviteForm')?.addEventListener('submit', e => {
    e.preventDefault();
    const form = e.target;
    fetch(`/kanban/${form.kanban_id.value}/invite`, {
      method: 'POST',
      headers: { 'X-CSRF-TOKEN': csrf() },
      body: new FormData(form)
    })
    .then(r => r.json())
    .then(json => {
      if (json.success) {
        bootstrap.Modal.getInstance(form.closest('.modal')).hide();
        alert('Invitation berhasil dikirim');
      }
    });
  });

});
