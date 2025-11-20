<?php
$pageTitle = 'Guide de Choix Support Impression | Quel matériau choisir ? | Imprixo';
$pageDescription = '🎯 Guide interactif pour choisir le bon support d\'impression : Dibond, Forex, Bâche, Textile. Trouvez le matériau idéal pour votre projet en 3 questions';
include __DIR__ . '/includes/header.php';
?>

<div class="container">
<h1>🎯 Quel support choisir pour votre projet ?</h1>
<div class="quiz">
<div class="question">
<h3>1. Où sera affiché votre impression ?</h3>
<div class="options">
<label class="option"><input type="radio" name="q1" value="interieur">📍 Intérieur uniquement</label>
<label class="option"><input type="radio" name="q1" value="exterieur-court">🌤️ Extérieur court terme (< 6 mois)</label>
<label class="option"><input type="radio" name="q1" value="exterieur-long">☀️ Extérieur longue durée (> 1 an)</label>
</div>
</div>
<div class="question">
<h3>2. Quel type de support préférez-vous ?</h3>
<div class="options">
<label class="option"><input type="radio" name="q2" value="rigide">📐 Rigide (panneau)</label>
<label class="option"><input type="radio" name="q2" value="souple">🎪 Souple (bâche, textile)</label>
</div>
</div>
<div class="question">
<h3>3. Quel est votre budget ?</h3>
<div class="options">
<label class="option"><input type="radio" name="q3" value="economique">💰 Économique (< 15€/m²)</label>
<label class="option"><input type="radio" name="q3" value="standard">💵 Standard (15-30€/m²)</label>
<label class="option"><input type="radio" name="q3" value="premium">💎 Premium (> 30€/m²)</label>
</div>
</div>
<button onclick="showResult()" style="width:100%;padding:16px;background:var(--primary);color:#fff;border:none;border-radius:8px;font-size:1.2rem;font-weight:700;cursor:pointer">Voir ma recommandation →</button>
</div>
<div class="result" id="result">
<h2>✅ Nous vous recommandons :</h2>
<div id="recommendation"></div>
<a href="/produits.html" class="btn">Voir les produits →</a>
</div>
</div>
<script>
function showResult(){
const q1=document.querySelector('input[name="q1"]:checked')?.value;
const q2=document.querySelector('input[name="q2"]:checked')?.value;
const q3=document.querySelector('input[name="q3"]:checked')?.value;
if(!q1||!q2||!q3){alert('Répondez aux 3 questions');return}
let rec='';
if(q2==='rigide'&&q3==='economique')rec='<h3 style="font-size:2rem;margin:20px 0">Forex 3-5mm</h3><p>PVC expansé léger, idéal intérieur et court terme extérieur. À partir de 12€/m²</p>';
else if(q2==='rigide'&&q1==='exterieur-long')rec='<h3 style="font-size:2rem;margin:20px 0">Dibond 3mm</h3><p>Aluminium composite premium, durée 5-7 ans extérieur. À partir de 25€/m²</p>';
else if(q2==='souple')rec='<h3 style="font-size:2rem;margin:20px 0">Bâche PVC 510g</h3><p>Bâche résistante, idéale tous usages. À partir de 18€/m²</p>';
else rec='<h3 style="font-size:2rem;margin:20px 0">Forex 10mm</h3><p>PVC expansé ultra-rigide pour grands formats. À partir de 22€/m²</p>';
document.getElementById('recommendation').innerHTML=rec;
document.querySelector('.quiz').style.display='none';
document.getElementById('result').classList.add('show');
}
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
