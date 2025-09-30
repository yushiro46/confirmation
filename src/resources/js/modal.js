const modal      = document.getElementById("detailModal");
const closeBtn   = document.getElementById("closeModalBtn");
const deleteBtn  = document.getElementById("deleteBtn");
const csrfToken  = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
let currentId    = null; // 今開いている行のIDを保持

const safeParse = (v) => {
  if (v == null || v === '' || v === 'null') return '';
  try { return JSON.parse(v); } catch { return v; }
};

document.querySelectorAll(".detail-btn").forEach(btn => {
  btn.addEventListener("click", function () {
    const d = this.dataset;
    currentId = d.id; // ← 削除用に保持

    document.getElementById("modalName").textContent     = d.name || '';
    document.getElementById("modalGender").textContent   = d.gender || '';
    document.getElementById("modalEmail").textContent    = d.email || '';
    document.getElementById("modalTel").textContent      = d.tel || '';
    document.getElementById("modalAddress").textContent  = safeParse(d.address);
    document.getElementById("modalBuilding").textContent = safeParse(d.building);
    document.getElementById("modalCategory").textContent = d.category || '';
    document.getElementById("modalDetail").textContent   = safeParse(d.detail);

    modal.style.display = "block";
  });
});

deleteBtn?.addEventListener("click", async () => {
  if (!currentId) return;
  if (!confirm('本当に削除しますか？')) return;

  try {
    const res = await fetch(`/contacts/${currentId}`, {
      method: 'DELETE',
      headers: {
        'X-CSRF-TOKEN': csrfToken,
        'X-Requested-With': 'XMLHttpRequest',
        'Accept': 'application/json',
      }
    });

    if (!res.ok) throw new Error(`HTTP ${res.status}`);
    const json = await res.json();
    if (json.ok) {
      // 行をDOMから削除
      const tr = document.querySelector(`tr[data-row-id="${currentId}"]`);
      tr?.parentNode?.removeChild(tr);

      // モーダル閉じる
      modal.style.display = "none";
      currentId = null;
    }
  } catch (e) {
    alert('削除に失敗しました。時間を置いて再度お試しください。');
    console.error(e);
  }
});

closeBtn?.addEventListener("click", () => { modal.style.display = "none"; });
window.addEventListener("click", (e) => { if (e.target === modal) modal.style.display = "none"; });

