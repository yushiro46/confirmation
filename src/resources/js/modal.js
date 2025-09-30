// モーダル要素を取得（これが無いと動かない）
const modal    = document.getElementById("detailModal");
const closeBtn = document.getElementById("closeModalBtn");

const safeParse = (v) => {
  if (v == null || v === '' || v === 'null') return '';
  try { return JSON.parse(v); } catch { return v; }
};

document.querySelectorAll(".detail-btn").forEach(btn => {
  btn.addEventListener("click", function () {
    const d = this.dataset;

    document.getElementById("modalName").textContent     = d.name || '';
    document.getElementById("modalGender").textContent   = d.gender || '';
    document.getElementById("modalEmail").textContent    = d.email || '';
    document.getElementById("modalTel").textContent      = d.tel || '';
    document.getElementById("modalAddress").textContent  = safeParse(d.address);
    document.getElementById("modalBuilding").textContent = safeParse(d.building);
    document.getElementById("modalCategory").textContent = d.category || '';
    document.getElementById("modalDetail").textContent   = safeParse(d.detail);

    if (modal) modal.style.display = "block";
  });
});

closeBtn?.addEventListener("click", () => { modal.style.display = "none"; });
window.addEventListener("click", (e) => { if (e.target === modal) modal.style.display = "none"; });
