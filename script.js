 // Undo stack to store actions for undo functionality
 let undoStore = [];
 // HashMap to store transfer details
 let hashmap = new Map();

 // Undo function to revert the last transfer
 function undo() {
     if (undoStore.length > 0) {
         const undObj = undoStore.pop();
         const source = document.getElementById(undObj.src);
         const destination = document.getElementById(undObj.dst);
         undObj.items.forEach(item => {
             hashmap.delete(item.value);
             source.appendChild(item);
         });
     }
 }

 // Transfer function to move items between lists
 function transfer(src, dst, all) {
     const source = document.getElementById(src);
     const destination = document.getElementById(dst);
     const srcHq = document.getElementById((src === 'from-list' ? 'hqlist1' : 'hqlist2')).value;
     const dstHq = document.getElementById((dst === 'from-list' ? 'hqlist1' : 'hqlist2')).value;

     // Ensure required fields are selected
     if (document.getElementById('hqlist1').value === 'Headquarter' ||
         document.getElementById('hqlist2').value === 'Headquarter' ||
         document.getElementById('role').value === 'Role' ||
         document.getElementById('role1').value === 'Role') {
         return;
     }

     // Collect items to transfer
     const items = Array.from(all === 'a' ?
         Array.from(source.options).filter(option => option.style.display !== 'none') :
         source.selectedOptions);

     // Move items and update hashmap
     items.forEach(item => {
         hashmap.set(item.value, { 'src': srcHq, 'dst': dstHq });
         destination.appendChild(item);
     });

     // Store the action for undo
     const undObj = { 'src': src, 'dst': dst, 'items': items };
     undoStore.push(undObj);
 }

 // Function to fetch headquarters based on crop type
 function fH(cropType, input) {
     const xhr = new XMLHttpRequest();
     xhr.open("GET", "?crop_type=" + cropType, true);
     xhr.onload = function() {
         if (this.status === 200) {
             const selectElem = document.getElementById(input === 'crop-type' ? 'hqlist1' : 'hqlist2');
             selectElem.innerHTML = this.responseText;
         }
     };
     xhr.send();
 }

 // Function to fetch users based on headquarters ID
 function fHq(hqid, input) {
     let output = input === 'hqlist1' ? 'from-list' : 'to-list';
     let roleid = input === 'hqlist1' ? 'role' : 'role1';
     fU(hqid, document.getElementById(roleid).value, output);
 }

 // Function to filter roles based on selection
 function fr(roleid, input) {
     const options = document.getElementById(input === 'role' ? 'role1' : 'role').options;
     Array.from(options).forEach(option => {
         if ((roleid === '4') && (option.value === '5' || option.value === '10' || option.value === '11')) {
             option.disabled = true;
             option.style.display = 'none';
         } else if ((roleid === '5') && (option.value === '10' || option.value === '11')) {
             option.disabled = true;
             option.style.display = 'none';
         } else if ((roleid === '10' || roleid === '11') && (option.value === '4' || option.value === '5')) {
             option.disabled = true;
             option.style.display = 'none';
         } else {
             option.disabled = false;
             option.style.display = '';
         }
     });

     let output = input === 'role' ? 'from-list' : 'to-list';
     let hqid = input === 'role' ? 'hqlist1' : 'hqlist2';
     fU(document.getElementById(hqid).value, roleid, output);
 }

 // Function to fetch users based on headquarters and role
 function fU(hqid, roleid, output) {
     if (hqid !== 'Headquarter' && roleid !== 'Role'&&hqid !==''&&roleid !=='') {
         const xhr = new XMLHttpRequest();
         const cropType = document.getElementById((output === 'to-list') ? 'crop-type1' : 'crop-type').value;
         xhr.open("GET", "?hqid=" + hqid + "&roleid=" + roleid + "&ct=" + cropType, true);
         xhr.onload = function() {
             if (this.status === 200) {
                 const selectElem = document.getElementById(output);
                 selectElem.innerHTML = this.responseText;
             }
         };
         xhr.send();
     }
 }

 // Function to search within lists
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

 // Function to submit the modifications
 function submit(input) {
     

     // Check if any modifications have been made
     if (hashmap.size === 0) {
         alert("No modifications have been made!!!");
         return;
     }

     // Change button text to indicate loading

     const submitButton = document.getElementById(input);
     submitButton.innerHTML='<i class="fa fa-circle-o-notch fa-spin"></i> LOADING';

     
     // Iterate over hashmap to send POST requests for each item
     

     const procesing=()=>{
     hashmap.forEach((item, index) => {
         const xhr = new XMLHttpRequest();
         xhr.open("POST", "index.php?action=insert&id=" + index, true);
         xhr.setRequestHeader("Content-Type", "application/json");

         xhr.onload = function() {
             if (xhr.status === 200) {
                 // Successful response, proceed with the next request
                 const xhr1 = new XMLHttpRequest();
                 xhr1.open("POST", "index.php?action=update&id=" + index + "&src=" + item.src + "&dst=" + item.dst, true);
                 xhr1.setRequestHeader("Content-Type", "application/json");

                 xhr1.onload = function() {
                     if (xhr1.status === 200) {
                         // Successfully processed second request
                         hashmap.delete(index);
                     } else {
                         alert("Server arguments error for second request!!!");
                     }
                 };

                 xhr1.onerror = function() {
                     alert("Error sending the second request!!!");
                 };

                 xhr1.send();
             } else {
                 // Error in the first request, handle accordingly
                 alert("Error sending the first request!!!");
             }
         };

         // Send the first request
         xhr.send();
     });

     // Update UI when all requests are complete
     
     submitButton.innerHTML = "SUBMIT";
     alert("Modification successfully");
     hashmap.clear();
     undoStore=[];}
     setTimeout(procesing,3000);
     
 }