document.addEventListener("DOMContentLoaded", function () {
    window.confirmDelete = function (event, athleteId) {
        event.preventDefault();

        Swal.fire({
            title: "Are you sure?",
            text: "This action cannot be undone!",
            icon: "warning",
            input: "text",
            inputPlaceholder: "Type 'delete!' to confirm",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Delete",
            preConfirm: (value) => {
                if (value !== "delete!") {
                    Swal.showValidationMessage("❌ You must type 'delete!' exactly!");
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                let form = document.querySelector(`#delete-form-${athleteId}`);
                if (form) {
                    form.submit();
                } else {
                    console.error(`❌ ERROR: Form #delete-form-${athleteId} not found!`);
                    alert(`Error: Delete form not found! (ID: delete-form-${athleteId})`);
                }
            }
        });
    };
   
    let genderState = 0;
    let sortStates = { familyName: 0, givenName: 0, dob: 0 }; 
    let tableBody = document.querySelector("table tbody");

    let originalOrder = Array.from(tableBody.querySelectorAll(".athlete-row")).map(row => row.cloneNode(true));

    document.getElementById("genderFilterBtn").addEventListener("click", function () {
        let rows = document.querySelectorAll(".athlete-row");
        genderState = (genderState + 1) % 3;

        if (genderState === 0) {
            tableBody.innerHTML = "";
            originalOrder.forEach(row => tableBody.appendChild(row.cloneNode(true)));
        } else {
            rows.forEach(row => {
                let gender = row.dataset.gender ? row.dataset.gender.trim().toLowerCase() : "";
                if (genderState === 1 && gender === "male") {
                    row.style.display = "";
                } else if (genderState === 2 && gender === "female") {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        }
    });

    function sortTableByColumn(columnIndex, type, stateKey) {
        let rows = Array.from(tableBody.rows);
        let currentState = sortStates[stateKey];

        if (currentState === 0) {
            rows.sort((a, b) => {
                let aValue = a.cells[columnIndex]?.textContent.trim().toLowerCase() || "";
                let bValue = b.cells[columnIndex]?.textContent.trim().toLowerCase() || "";
                if (type === "date") {
                    return new Date(aValue) - new Date(bValue);
                }
                return aValue.localeCompare(bValue);
            });
            sortStates[stateKey] = 1;
        } else if (currentState === 1) {
            rows.sort((a, b) => {
                let aValue = a.cells[columnIndex]?.textContent.trim().toLowerCase() || "";
                let bValue = b.cells[columnIndex]?.textContent.trim().toLowerCase() || "";
                if (type === "date") {
                    return new Date(bValue) - new Date(aValue);
                }
                return bValue.localeCompare(aValue);
            });
            sortStates[stateKey] = 2;
        } else {
            tableBody.innerHTML = "";
            originalOrder.forEach(row => tableBody.appendChild(row.cloneNode(true)));
            sortStates[stateKey] = 0;
            return;
        }

        tableBody.innerHTML = "";
        rows.forEach(row => tableBody.appendChild(row));
    }

    document.getElementById("familyNameFilterBtn").addEventListener("click", function () {
        sortTableByColumn(2, "text", "familyName");
    });

    document.getElementById("givenNameFilterBtn").addEventListener("click", function () {
        sortTableByColumn(3, "text", "givenName");
    });

    document.getElementById("dobFilterBtn").addEventListener("click", function () {
        sortTableByColumn(4, "date", "dob"); 
    });

    document.getElementById('searchInput').addEventListener('input', function () {
        let searchQuery = this.value.toLowerCase();
        document.querySelectorAll('.athlete-row').forEach(row => {
            let familyName = row.querySelector('td:nth-child(3)').textContent.toLowerCase();
            let givenName = row.querySelector('td:nth-child(4)').textContent.toLowerCase();
    
            if (familyName.includes(searchQuery) || givenName.includes(searchQuery)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
