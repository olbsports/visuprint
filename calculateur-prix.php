<?php
$pageTitle = 'Calculateur Prix Impression Grand Format Temps Réel | Imprixo';
$pageDescription = '💰 Calculez instantanément le prix de votre impression grand format : Dibond, Forex, Bâche. Prix dégressifs automatiques. Devis immédiat sans engagement';
include __DIR__ . '/includes/header.php';
?>

<div class="container">
<div class="calculator">
<h1>💰 Calculateur de Prix</h1>
<p style="color:#6c757d;margin-bottom:30px;font-size:1.05rem">Obtenez instantanément le prix de votre impression avec les tarifs dégressifs</p>

<form id="calcForm">
<div class="form-group">
<label>1. Choisissez votre support</label>
<div class="support-cards">
<label class="support-card active">
<input type="radio" name="support" value="dibond" checked>
<div class="support-icon">📐</div>
<div style="font-weight:700">Dibond 3mm</div>
<small style="color:#6c757d">Premium alu</small>
</label>
<label class="support-card">
<input type="radio" name="support" value="forex10">
<div class="support-icon">📋</div>
<div style="font-weight:700">Forex 10mm</div>
<small style="color:#6c757d">PVC rigide</small>
</label>
<label class="support-card">
<input type="radio" name="support" value="forex3">
<div class="support-icon">📄</div>
<div style="font-weight:700">Forex 3mm</div>
<small style="color:#6c757d">PVC léger</small>
</label>
<label class="support-card">
<input type="radio" name="support" value="bache">
<div class="support-icon">🎪</div>
<div style="font-weight:700">Bâche 510g</div>
<small style="color:#6c757d">PVC souple</small>
</label>
</div>
</div>

<div class="form-group">
<label>2. Dimensions (cm)</label>
<div class="input-group">
<div>
<label style="font-weight:400;font-size:0.9rem">Largeur</label>
<input type="number" id="largeur" value="120" min="10" max="500" step="1">
</div>
<div>
<label style="font-weight:400;font-size:0.9rem">Hauteur</label>
<input type="number" id="hauteur" value="80" min="10" max="500" step="1">
</div>
</div>
</div>

<div class="form-group">
<label>3. Quantité d'exemplaires</label>
<input type="number" id="quantite" value="1" min="1" max="999">
<small style="color:#6c757d">💡 Prix dégressifs automatiques dès 10m²</small>
</div>

<div class="form-group">
<label>4. Finitions (optionnel)</label>
<select id="finition">
<option value="0">Sans finition</option>
<option value="3">Pelliculage mat (+3€/m²)</option>
<option value="3">Pelliculage brillant (+3€/m²)</option>
<option value="8">Découpe à forme (+8€/m²)</option>
</select>
</div>

<div class="form-group">
<label>5. Délai</label>
<select id="delai">
<option value="0">Standard 48-72h</option>
<option value="30">Express 24h (+30%)</option>
<option value="50">Urgent 12h (+50%)</option>
</select>
</div>
</form>
</div>

<div class="result">
<div class="surface-display">
<div style="color:#6c757d;margin-bottom:10px">Surface totale</div>
<div class="surface-value" id="surfaceTotal">0.96</div>
<div style="color:#6c757d">m² (1 ex.)</div>
</div>

<div class="price-display">
<div class="price-label">Prix total TTC</div>
<div class="price-value" id="priceTotal">28,80€</div>
<div class="price-unit">Livraison France incluse</div>
</div>

<div class="details">
<div class="detail-row">
<span class="detail-label">Prix unitaire</span>
<span class="detail-value" id="prixUnitaire">30€/m²</span>
</div>
<div class="detail-row">
<span class="detail-label">Surface unitaire</span>
<span class="detail-value" id="surfaceUnitaire">0.96 m²</span>
</div>
<div class="detail-row">
<span class="detail-label">Quantité</span>
<span class="detail-value" id="qte">1 ex.</span>
</div>
<div class="detail-row" id="finitionRow" style="display:none">
<span class="detail-label">Finition</span>
<span class="detail-value" id="finitionDetail">-</span>
</div>
<div class="detail-row" id="delaiRow" style="display:none">
<span class="detail-label">Supplément délai</span>
<span class="detail-value" id="delaiDetail">-</span>
</div>
</div>

<div class="degressive" id="degressiveMsg">
<strong>💡 Prix dégressif appliqué !</strong><br>
Économisez <span id="savings">0€</span> grâce à la quantité commandée.
</div>

<button class="btn" onclick="window.location.href='/devis-express.html'">
Valider et commander →
</button>

<p style="text-align:center;color:#6c757d;margin-top:20px;font-size:0.9rem">
Prix indicatif · Devis détaillé après validation
</p>
</div>
</div>

<script>
const pricing={
dibond:{base:30,deg10:28,deg20:26,deg50:25,name:'Dibond 3mm'},
forex10:{base:24,deg10:22,deg20:20,deg50:19,name:'Forex 10mm'},
forex3:{base:14,deg10:12,deg20:10,deg50:9,name:'Forex 3mm'},
bache:{base:20,deg10:18,deg20:16,deg50:14,name:'Bâche 510g'}
};

function calculate(){
const support=document.querySelector('input[name="support"]:checked').value;
const largeur=parseFloat(document.getElementById('largeur').value)||0;
const hauteur=parseFloat(document.getElementById('hauteur').value)||0;
const quantite=parseInt(document.getElementById('quantite').value)||1;
const finition=parseFloat(document.getElementById('finition').value)||0;
const delai=parseFloat(document.getElementById('delai').value)||0;

const surfaceUnit=(largeur/100)*(hauteur/100);
const surfaceTotal=surfaceUnit*quantite;

let prixM2=pricing[support].base;
if(surfaceTotal>=50)prixM2=pricing[support].deg50;
else if(surfaceTotal>=20)prixM2=pricing[support].deg20;
else if(surfaceTotal>=10)prixM2=pricing[support].deg10;

const prixBase=surfaceTotal*prixM2;
const prixFinition=finition*surfaceTotal;
const prixSousTotal=prixBase+prixFinition;
const suppDelai=prixSousTotal*(delai/100);
const prixTotal=prixSousTotal+suppDelai;

document.getElementById('surfaceTotal').textContent=surfaceTotal.toFixed(2);
document.getElementById('surfaceUnitaire').textContent=surfaceUnit.toFixed(2)+' m²';
document.getElementById('qte').textContent=quantite+' ex.';
document.getElementById('prixUnitaire').textContent=prixM2.toFixed(2)+'€/m²';
document.getElementById('priceTotal').textContent=prixTotal.toFixed(2)+'€';

if(finition>0){
document.getElementById('finitionRow').style.display='flex';
document.getElementById('finitionDetail').textContent='+'+prixFinition.toFixed(2)+'€';
}else{
document.getElementById('finitionRow').style.display='none';
}

if(delai>0){
document.getElementById('delaiRow').style.display='flex';
document.getElementById('delaiDetail').textContent='+'+suppDelai.toFixed(2)+'€';
}else{
document.getElementById('delaiRow').style.display='none';
}

const savings=(pricing[support].base-prixM2)*surfaceTotal;
if(savings>0){
document.getElementById('degressiveMsg').style.display='block';
document.getElementById('savings').textContent=savings.toFixed(2)+'€';
}else{
document.getElementById('degressiveMsg').style.display='none';
}
}

document.querySelectorAll('input[name="support"]').forEach(radio=>{
radio.addEventListener('change',e=>{
document.querySelectorAll('.support-card').forEach(c=>c.classList.remove('active'));
e.target.closest('.support-card').classList.add('active');
calculate();
});
});

document.getElementById('largeur').addEventListener('input',calculate);
document.getElementById('hauteur').addEventListener('input',calculate);
document.getElementById('quantite').addEventListener('input',calculate);
document.getElementById('finition').addEventListener('change',calculate);
document.getElementById('delai').addEventListener('change',calculate);

calculate();
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>
