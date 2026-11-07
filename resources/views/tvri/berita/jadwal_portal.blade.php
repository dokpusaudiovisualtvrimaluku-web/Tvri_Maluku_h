<!doctype html>
<html lang="id">
<head>
<link rel="icon" href="{{ asset('images/logo_tvri.png') }}" type="image/png">
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Jadwal Peliputan Berita – TVRI Maluku</title>
<style>
  :root{
    --bg:#F3F5F9; --surface:#FFFFFF; --ink:#0B1A33; --muted:#5B6B85; --line:#E3E8F0;
    --brand:#0B3D91; --brand-soft:#E8EFFB; --live:#D93A3F; --live-soft:#FDECEC; --warn:#B7791F; --warn-soft:#FDF4E3;
  }
  *{box-sizing:border-box;margin:0;padding:0}
  body{font-family:system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif;background:var(--bg);color:var(--ink);font-size:14px;line-height:1.5}
  .mono{font-family:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,monospace}
  button{font:inherit;color:inherit;cursor:pointer;border:0;background:none}
  button:focus-visible,input:focus-visible,select:focus-visible,a:focus-visible{outline:2px solid var(--brand);outline-offset:2px}

  header.top{background:var(--ink);color:#fff;padding:20px 20px 72px}
  .wrap{max-width:1180px;margin:0 auto}
  .brandrow{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap}
  .logo{display:flex;align-items:center;gap:10px}
  .logo-anim{--w:92px;position:relative;display:block;width:var(--w);aspect-ratio:289/171;flex:none}
  .logo-anim img{position:absolute;inset:0;width:100%;height:100%;object-fit:contain}
  .lg-ri{transform-origin:70.1% 49.4%;animation:ri-in .9s cubic-bezier(.34,1.56,.64,1) both, ri-float 4s ease-in-out 1.2s infinite}
  .lg-tv{animation:tv-in .7s cubic-bezier(.22,1,.36,1) .35s both}
  .ring{position:absolute;left:70.1%;top:49.4%;width:calc(var(--w)*.585);aspect-ratio:1;border:2px solid rgba(255,255,255,.55);border-radius:50%;transform:translate(-50%,-50%) scale(1);opacity:0;animation:ring 2.8s ease-out 1.1s infinite}
  .ring.r2{animation-delay:2.5s}
  .shine{position:absolute;left:70.1%;top:49.4%;width:calc(var(--w)*.585);aspect-ratio:1;border-radius:50%;transform:translate(-50%,-50%);overflow:hidden;pointer-events:none;
    --mpos:calc(var(--w)*-0.4085) 0;--msize:var(--w) calc(var(--w)*0.5917);
    -webkit-mask:url(/images/jadwal/logo-ri.png) var(--mpos)/var(--msize) no-repeat;mask:url(/images/jadwal/logo-ri.png) var(--mpos)/var(--msize) no-repeat}
  .shine::before{content:"";position:absolute;inset:-20% auto -20% -60%;width:40%;background:linear-gradient(100deg,transparent,rgba(11,61,145,.45),transparent);transform:skewX(-15deg);animation:shine 4.5s ease-in-out 1.4s infinite}
  @keyframes ri-in{0%{opacity:0;transform:scale(.2) rotate(-120deg)}100%{opacity:1;transform:scale(1) rotate(0)}}
  @keyframes tv-in{0%{opacity:0;transform:translateX(-28px);filter:blur(4px)}100%{opacity:1;transform:none;filter:none}}
  @keyframes ri-float{0%,100%{transform:scale(1)}50%{transform:scale(1.04)}}
  @keyframes ring{0%{opacity:.7;transform:translate(-50%,-50%) scale(1)}100%{opacity:0;transform:translate(-50%,-50%) scale(1.9)}}
  @keyframes shine{0%,60%{left:-60%}100%{left:130%}}
  @media (prefers-reduced-motion:reduce){.lg-ri,.lg-tv,.ring,.shine::before{animation:none}.ring{display:none}}
  .logo small{display:block;color:#9FB0CC;font-size:12px}
  .logo strong{font-size:14px}
  .onair{display:inline-flex;align-items:center;gap:8px;border:1px solid #2A3B5C;border-radius:999px;padding:5px 12px;font-size:12px;color:#C9D4E8}
  .dot{width:8px;height:8px;border-radius:50%;background:var(--live);box-shadow:0 0 0 0 rgba(217,58,63,.7);animation:pulse 1.8s infinite}
  @keyframes pulse{70%{box-shadow:0 0 0 8px rgba(217,58,63,0)}100%{box-shadow:0 0 0 0 rgba(217,58,63,0)}}
  @media (prefers-reduced-motion:reduce){.dot{animation:none}}
  h1{font-size:clamp(24px,4vw,34px);font-weight:700;letter-spacing:-.02em;margin-top:22px;text-wrap:balance}
  .sub{color:#9FB0CC;margin-top:4px}

  .stats{display:grid;grid-template-columns:repeat(4,1fr);gap:12px;margin-top:-52px}
  .stat{background:var(--surface);border:1px solid var(--line);border-radius:14px;padding:14px 16px;box-shadow:0 6px 20px -12px rgba(11,26,51,.25)}
  .stat span{color:var(--muted);font-size:12px}
  .stat b{display:block;font-size:26px;font-weight:700;letter-spacing:-.02em;margin-top:2px}
  .stat.live b{color:var(--live)}

  .days{display:flex;gap:8px;overflow-x:auto;padding:18px 0 4px;scrollbar-width:none}
  .day{flex:1 0 auto;min-width:92px;background:var(--surface);border:1px solid var(--line);border-radius:12px;padding:10px 12px;text-align:left;transition:border-color .15s,background .15s}
  .day:hover{border-color:#B9C6DC}
  .day small{display:block;color:var(--muted);font-size:11px;text-transform:uppercase;letter-spacing:.06em}
  .day strong{font-size:18px}
  .day em{font-style:normal;font-size:11px;color:var(--muted);margin-left:6px}
  .day[aria-pressed="true"]{background:var(--brand);border-color:var(--brand);color:#fff}
  .day[aria-pressed="true"] small,.day[aria-pressed="true"] em{color:#C7D6F2}

  .toolbar{display:flex;gap:10px;flex-wrap:wrap;align-items:center;margin-top:14px}
  .search{flex:1 1 220px;display:flex;align-items:center;gap:8px;background:var(--surface);border:1px solid var(--line);border-radius:10px;padding:0 12px}
  .search input{border:0;background:none;padding:10px 0;width:100%;font:inherit;outline:none}
  select{font:inherit;background:var(--surface);border:1px solid var(--line);border-radius:10px;padding:9px 12px;color:var(--ink)}
  .chips{display:flex;gap:6px;flex-wrap:wrap;margin-top:10px}
  .chip{border:1px solid var(--line);background:var(--surface);border-radius:999px;padding:5px 12px;font-size:12px;color:var(--muted)}
  .chip[aria-pressed="true"]{background:var(--ink);border-color:var(--ink);color:#fff}

  .layout{display:grid;grid-template-columns:1fr 300px;gap:20px;margin:20px auto 40px}
  .group h2{font-size:13px;text-transform:uppercase;letter-spacing:.08em;color:var(--muted);margin:18px 0 10px;display:flex;align-items:center;gap:10px}
  .group h2::after{content:"";flex:1;height:1px;background:var(--line)}
  .group:first-child h2{margin-top:0}

  .card{display:grid;grid-template-columns:84px 1fr;background:var(--surface);border:1px solid var(--line);border-radius:14px;overflow:hidden;margin-bottom:10px;transition:box-shadow .15s,transform .15s}
  .card:hover{box-shadow:0 10px 24px -16px rgba(11,26,51,.35)}
  .time{padding:14px 12px;border-right:1px solid var(--line);background:#FAFBFD}
  .time b{display:block;font-size:16px}
  .time span{color:var(--muted);font-size:12px}
  .card.is-live .time{background:var(--live-soft);border-right-color:#F6CACC}
  .card.is-live .time b{color:var(--live)}
  .body{padding:14px 16px;min-width:0}
  .meta{display:flex;gap:6px;flex-wrap:wrap;align-items:center;margin-bottom:6px}
  .tag{font-size:11px;font-weight:600;border-radius:6px;padding:2px 8px;background:var(--brand-soft);color:var(--brand)}
  .tag.live{background:var(--live);color:#fff}
  .tag.p-Penting{background:var(--warn-soft);color:var(--warn)}
  .tag.p-Mendesak{background:var(--live-soft);color:var(--live)}
  .tag.status{background:#EEF1F5;color:var(--muted);font-weight:500}
  .id{margin-left:auto;font-size:11px;color:#94A3B8}
  .body h3{font-size:15px;font-weight:600;letter-spacing:-.01em;text-wrap:pretty}
  .loc{display:inline-flex;align-items:center;gap:5px;color:var(--muted);font-size:13px;margin-top:3px;text-decoration:none}
  .loc:hover{color:var(--brand);text-decoration:underline}
  .team{display:flex;flex-wrap:wrap;gap:6px 14px;margin-top:10px;padding-top:10px;border-top:1px dashed var(--line);font-size:12.5px}
  .team div{display:flex;align-items:center;gap:6px}
  .av{width:22px;height:22px;border-radius:50%;display:grid;place-items:center;font-size:10px;font-weight:700;color:#fff;background:var(--brand);flex:none}
  .av.k{background:#2F855A}.av.d{background:#6B46C1}.av.v{background:#475569;border-radius:6px}
  .team small{color:var(--muted);display:block;font-size:10.5px;line-height:1}
  .note{margin-top:10px;font-size:12.5px;color:#334155;background:#F7F9FC;border-left:3px solid var(--brand);padding:8px 10px;border-radius:0 8px 8px 0}
  .empty{background:var(--surface);border:1px dashed #C9D3E3;border-radius:14px;padding:40px;text-align:center;color:var(--muted)}

  aside .panel{background:var(--surface);border:1px solid var(--line);border-radius:14px;padding:16px;margin-bottom:14px}
  aside h2{font-size:13px;font-weight:600;margin-bottom:12px;display:flex;justify-content:space-between}
  aside h2 span{color:var(--muted);font-weight:400}
  .load{display:grid;grid-template-columns:1fr auto;gap:4px 10px;align-items:center;margin-bottom:10px;font-size:12.5px}
  .load button{text-align:left;overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
  .load button:hover{color:var(--brand)}
  .load .role{color:var(--muted);font-size:11px}
  .bar{grid-column:1/-1;height:6px;background:#EEF1F5;border-radius:99px;overflow:hidden}
  .bar i{display:block;height:100%;background:var(--brand);border-radius:99px}
  .veh{display:flex;justify-content:space-between;align-items:center;padding:8px 0;border-top:1px solid var(--line);font-size:12.5px}
  .veh:first-of-type{border-top:0}
  .veh small{color:var(--muted);display:block}
  .cond{font-size:11px;padding:2px 8px;border-radius:6px;background:#E6F4EA;color:#2F855A;white-space:nowrap}
  .cond.bad{background:var(--warn-soft);color:var(--warn)}
  .legend{font-size:12px;color:var(--muted);display:grid;gap:6px}
  .legend div{display:flex;align-items:center;gap:8px}
  .sw{width:10px;height:10px;border-radius:3px}

  footer{color:var(--muted);font-size:12px;text-align:center;padding:0 20px 30px}
  .main{padding:0 20px}

  @media (max-width:900px){.layout{grid-template-columns:1fr}.stats{grid-template-columns:repeat(2,1fr)}}
  @media (max-width:520px){.card{grid-template-columns:1fr}.time{display:flex;gap:8px;align-items:baseline;border-right:0;border-bottom:1px solid var(--line);padding:10px 14px}.time b{display:inline;font-size:15px}.id{display:none}}
</style>
</head>
<body>
<header class="top">
  <div class="wrap">
    <div class="brandrow">
      <div class="logo">
        <span class="logo-anim" role="img" aria-label="TVRI">
          <span class="ring" aria-hidden="true"></span><span class="ring r2" aria-hidden="true"></span>
          <img class="lg-tv" src="{{ asset('images/jadwal/logo-tv.png') }}" onerror="this.src='{{ asset('images/logo_tvri.png') }}'" alt="TVRI" />
          <img class="lg-ri" src="{{ asset('images/jadwal/logo-ri.png') }}" onerror="this.style.display='none'" alt="" />
          <span class="shine" aria-hidden="true"></span>
        </span>
        <div><strong>Stasiun Maluku</strong><small>Desk Pemberitaan</small></div>
      </div>
      <span class="onair"><span class="dot" aria-hidden="true"></span>Rencana Penugasan Crew &bull; Live Data</span>
    </div>
    <h1>Jadwal Peliputan Berita</h1>
    <p class="sub">Rencana Penugasan Crew &bull; Live Real-Time</p>
  </div>
</header>

<div class="main">
  <div class="wrap">
    <section class="stats" aria-label="Ringkasan">
      <div class="stat"><span>Total liputan</span><b id="s-total">0</b></div>
      <div class="stat live"><span>Siaran langsung</span><b id="s-live">0</b></div>
      <div class="stat"><span>Prioritas penting / mendesak</span><b id="s-prio">0</b></div>
      <div class="stat"><span>Crew lapangan bertugas</span><b id="s-crew">0</b></div>
    </section>

    <nav class="days" id="days" aria-label="Pilih hari"></nav>

    <div class="toolbar">
      <label class="search">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#5B6B85" stroke-width="2" aria-hidden="true"><circle cx="11" cy="11" r="7"/><path d="m20 20-3.5-3.5"/></svg>
        <span class="sr" style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0)">Cari liputan</span>
        <input id="q" type="search" placeholder="Cari agenda, lokasi, atau nama crew…" />
      </label>
      <label style="position:absolute;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0)" for="crewSel">Filter crew</label>
      <select id="crewSel"><option value="">Semua crew</option></select>
    </div>
    <div class="chips" id="chips" role="group" aria-label="Filter jenis liputan"></div>

    <div class="layout">
      <section id="list" aria-live="polite"></section>
      <aside>
        <div class="panel">
          <h2>Beban kerja crew <span id="loadScope">periode ini</span></h2>
          <div id="load"></div>
        </div>
        <div class="panel">
          <h2>Kendaraan operasional</h2>
          <div id="veh"></div>
        </div>
        <div class="panel legend">
          <div><span class="sw" style="background:var(--live)"></span>Siaran langsung / Mendesak</div>
          <div><span class="sw" style="background:var(--warn)"></span>Prioritas penting</div>
          <div><span class="sw" style="background:var(--brand)"></span>Liputan reguler</div>
        </div>
      </aside>
    </div>
  </div>
</div>
<footer>Terhubung secara real-time ke Pangkalan Data Newsroom &amp; Command Center TVRI Stasiun Maluku.</footer>

<script>
const data = @json($jadwalData);
const crew = @json($crewData);
const kendaraan = @json($kendaraanData);

const HARI = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
const BLN = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
const d = s => new Date(s + 'T00:00:00');
const longDate = s => { const x = d(s); return `${HARI[x.getDay()]}, ${x.getDate()} ${BLN[x.getMonth()]} ${x.getFullYear()}`; };
const initials = n => String(n || '').split(' ').filter(Boolean).map(p => p[0]).slice(0,2).join('').toUpperCase();
const dur = (a,b) => { 
  if (!a || !b) return '2 jam';
  const m = (t)=>{const[h,mm]=t.split(':');return +h*60+ +mm}; 
  const x = m(b)-m(a); 
  return (x>=60?Math.floor(x/60)+' jam ':'')+(x%60?x%60+' mnt':'').trim() || '2 jam'; 
};
const esc = s => String(s ?? '').replace(/[&<>"]/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));

const state = { day: '', jenis: '', q: '', crew: '' };
const dates = [...new Set(data.map(r => r.tanggal))].filter(Boolean);
const jenisList = [...new Set(data.map(r => r.jenis))].filter(Boolean);
const field = crew.filter(c => ['Reporter','Kamerawan','Driver'].includes(c.jabatan));

if (document.getElementById('koor')) document.getElementById('koor').textContent = data[0]?.koordinator || '';
document.getElementById('s-total').textContent = data.length;
document.getElementById('s-live').textContent = data.filter(r => r.jenis === 'Live' || (r.agenda && r.agenda.toLowerCase().includes('live')) || r.status === 'On Air').length;
document.getElementById('s-prio').textContent = data.filter(r => r.prioritas !== 'Biasa').length;
document.getElementById('s-crew').textContent = new Set(data.flatMap(r => [r.reporter, r.kamerawan, r.driver]).filter(x => x && x !== '-')).size;

const crewSel = document.getElementById('crewSel');
['Reporter','Kamerawan','Driver'].forEach(role => {
  const g = document.createElement('optgroup'); g.label = role;
  field.filter(c => c.jabatan === role).forEach(c => g.append(new Option(c.nama, c.nama)));
  crewSel.append(g);
});

function renderDays(){
  const el = document.getElementById('days');
  const items = [{v:'',top:'Semua',big:'Semua',n:data.length}, ...dates.map(s => { const x=d(s); return {v:s,top:HARI[x.getDay()],big:`${x.getDate()} ${BLN[x.getMonth()]}`,n:data.filter(r=>r.tanggal===s).length}; })];
  el.innerHTML = items.map(i => `<button class="day" aria-pressed="${state.day===i.v}" data-day="${i.v}"><small>${i.top}</small><strong>${i.big}</strong><em>${i.n} liputan</em></button>`).join('');
}
function renderChips(){
  document.getElementById('chips').innerHTML = ['', ...jenisList].map(j => `<button class="chip" aria-pressed="${state.jenis===j}" data-jenis="${esc(j)}">${j || 'Semua jenis'}</button>`).join('');
}
function filtered(){
  const q = state.q.toLowerCase();
  return data.filter(r =>
    (!state.day || r.tanggal === state.day) &&
    (!state.jenis || r.jenis === state.jenis) &&
    (!state.crew || [r.reporter,r.kamerawan,r.driver].includes(state.crew)) &&
    (!q || [r.agenda,r.lokasi,r.reporter,r.kamerawan,r.driver,r.jenis].join(' ').toLowerCase().includes(q))
  ).sort((a,b) => (a.tanggal+a.mulai).localeCompare(b.tanggal+b.mulai));
}
function person(name, role, cls){
  if (!name || name === '-') return '';
  return `<div><span class="av ${cls}" aria-hidden="true">${initials(name)}</span><span><small>${role}</small>${esc(name)}</span></div>`;
}
function card(r){
  const live = r.jenis === 'Live' || (r.agenda && r.agenda.toLowerCase().includes('live')) || r.status === 'On Air';
  return `<article class="card ${live?'is-live':''}">
    <div class="time mono"><b>${r.mulai}</b><span>s.d. ${r.selesai}</span><span style="display:block">${dur(r.mulai,r.selesai)}</span></div>
    <div class="body">
      <div class="meta">
        <span class="tag ${live?'live':''}">${live?'● LIVE':esc(r.jenis)}</span>
        ${r.prioritas!=='Biasa'?`<span class="tag p-${r.prioritas}">${r.prioritas}</span>`:''}
        <span class="tag status">${esc(r.status)}</span>
        <span class="id mono">${esc(r.id)}</span>
      </div>
      <h3>${esc(r.agenda)}</h3>
      <a class="loc" href="${esc(r.link)}" target="_blank" rel="noopener">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M12 22s7-6.2 7-12a7 7 0 1 0-14 0c0 5.8 7 12 7 12z"/><circle cx="12" cy="10" r="2.5"/></svg>${esc(r.lokasi)}</a>
      <div class="team">
        ${person(r.reporter,'Reporter','')}${person(r.kamerawan,'Kamerawan','k')}${person(r.driver,'Driver','d')}
        ${r.kendaraan && r.kendaraan!=='-' ? `<div><span class="av v" aria-hidden="true"><svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2.4"><path d="M3 13h18M5 17h14M6 13l2-6h8l2 6"/><circle cx="7.5" cy="17" r="1.5"/><circle cx="16.5" cy="17" r="1.5"/></svg></span><span><small>Kendaraan</small><span class="mono">${esc(r.kendaraan)}</span></span></div>` : ''}
      </div>
      ${r.catatan?`<p class="note"><strong>Catatan EIC:</strong> ${esc(r.catatan)}</p>`:''}
    </div>
  </article>`;
}
function renderList(){
  const rows = filtered(), el = document.getElementById('list');
  if (!rows.length){ el.innerHTML = `<div class="empty">Tidak ada liputan yang cocok dengan filter.</div>`; return; }
  const groups = {};
  rows.forEach(r => (groups[r.tanggal] ||= []).push(r));
  el.innerHTML = Object.entries(groups).map(([t, rs]) => `<div class="group"><h2>${longDate(t)} · ${rs.length} liputan</h2>${rs.map(card).join('')}</div>`).join('');
}
function renderLoad(){
  const scope = data.filter(r => !state.day || r.tanggal === state.day);
  document.getElementById('loadScope').textContent = state.day ? HARI[d(state.day).getDay()] : 'periode ini';
  const counts = field.map(c => ({...c, n: scope.filter(r => [r.reporter,r.kamerawan,r.driver].includes(c.nama)).length}));
  const max = Math.max(1, ...counts.map(c => c.n));
  document.getElementById('load').innerHTML = counts.sort((a,b)=>b.n-a.n).map(c => `
    <div class="load">
      <button data-crew="${esc(c.nama)}" aria-pressed="${state.crew===c.nama}" style="${state.crew===c.nama?'color:var(--brand);font-weight:600':''}">${esc(c.nama)} <span class="role">· ${c.jabatan}</span></button>
      <span class="mono">${c.n}</span>
      <div class="bar"><i style="width:${c.n/max*100}%;background:${c.jabatan==='Kamerawan'?'#2F855A':c.jabatan==='Driver'?'#6B46C1':'var(--brand)'}"></i></div>
    </div>`).join('');
}
function renderVeh(){
  document.getElementById('veh').innerHTML = kendaraan.map(k => `<div class="veh"><div><span class="mono">${esc(k.nopol)}</span><small>${esc(k.jenis)} · ${data.filter(r=>r.kendaraan===k.nopol).length}× dipakai</small></div><span class="cond ${k.kondisi!=='Baik'?'bad':''}">${esc(k.kondisi)}</span></div>`).join('');
}
function render(){ renderDays(); renderChips(); renderList(); renderLoad(); crewSel.value = state.crew; }

document.addEventListener('click', e => {
  const b = e.target.closest('button'); if (!b) return;
  if ('day' in b.dataset) state.day = b.dataset.day;
  else if ('jenis' in b.dataset) state.jenis = b.dataset.jenis;
  else if ('crew' in b.dataset) state.crew = state.crew === b.dataset.crew ? '' : b.dataset.crew;
  else return;
  render();
});
document.getElementById('q').addEventListener('input', e => { state.q = e.target.value; renderList(); });
crewSel.addEventListener('change', e => { state.crew = e.target.value; render(); });

renderVeh(); render();
</script>
</body>
</html>
