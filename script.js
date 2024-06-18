function transfer(src, dst, all) {
    const source = document.getElementById(src);
    const destination = document.getElementById(dst);
    
    const items = Array.from(all === 'a'? Array.from(source.options).filter(option => option.style.display !== 'none'): source.selectedOptions);
    items.forEach(item => destination.appendChild(item));
}
function fH(ct) {
    const xhr = new XMLHttpRequest();
    xhr.open("GET", "?crop_type=" + ct, true);
    xhr.onload = function() {
        if (this.status === 200) {
            document.getElementById('hqlist1').innerHTML = this.responseText;
            document.getElementById('hqlist2').innerHTML = this.responseText;
        }
    }
    xhr.send();
}

function fHq(hqid,input) {
    let output =(input==='hqlist1'?'from-list':'to-list');
    let roleid=(input==='hqlist1'?'role':'role1')
    fU(hqid, document.getElementById(roleid).value,output);
}

function fr(roleid,input) {
    let output =(input==='role'?'from-list':'to-list');
    let hqid=(input==='role'?'hqlist1':'hqlist2');
    fU(document.getElementById(hqid).value, roleid,output);
}

function fU(hqid, roleid,output) {
    console.log(hqid + roleid + output);
    if (hqid !== 'Headquarter' && roleid !== 'Role') {
        const xhr = new XMLHttpRequest();
        const ct = document.getElementById('crop-type').value;
        xhr.open("GET", "?hqid=" + hqid + "&roleid=" + roleid + "&ct=" + ct, true);
        xhr.onload = function() {
            if (this.status === 200) {
                document.getElementById(output).innerHTML = this.responseText;
            }
        }
        xhr.send();
    }
}

function search(input, id) {
    const list = document.getElementById(id);
    const terms = input.split(/[,/\s-]+/).map(term => term.trim().toUpperCase());
    const options = Array.from(list.options);
    
    options.forEach(option => {
        const text = option.textContent.toUpperCase();
        const value = option.value.toUpperCase();
        const match = terms.some(term => text.includes(term) || value.includes(term));
        option.style.display = match ? '' : 'none';
    });
}