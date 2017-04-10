/**
 * Created by Grégoire on 27/02/2015.
 */
window.onload = function ()
{
    debut = new Date();
    debut = debut.getTime();
    document.getElementById('compteur').innerHTML = temps +'s';
    cmp = setInterval('decompte();',990);
}

function decompte()
{
    var tmp = new Date();
    tmp = tmp.getTime();
    tmp = temps - ((tmp - debut)/1000);
    if (tmp > 0) {
        document.getElementById('compteur').innerHTML = Math.round(tmp) +'s';
    }
    else {
        clearInterval(cmp); // sinon le script se sent plus et il s'arrete pu
    }
}