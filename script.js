function transfer(src, dst, all) {
    const source = document.getElementById(src);
    const destination = document.getElementById(dst);
    const items = Array.from(all === 'a' ? source.options : source.selectedOptions);
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

function fHq(hqid) {
    fU(hqid, document.getElementById('role').value);
}

function fr(roleid) {
    fU(document.getElementById('hqlist1').value, roleid);
}

function fU(hqid, roleid) {
    if (hqid !== 'Headquarter' && roleid !== 'Role') {
        const xhr = new XMLHttpRequest();
        const ct = document.getElementById('crop-type').value;
        xhr.open("GET", "?hqid=" + hqid + "&roleid=" + roleid + "&ct=" + ct, true);
        xhr.onload = function() {
            if (this.status === 200) {
                document.getElementById('from-list').innerHTML = this.responseText;
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