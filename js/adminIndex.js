function updateStats() {
    // make AJAX request
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // parse JSON response
            var data = JSON.parse(this.responseText);

            // update HTML
            document.getElementById("total-users").innerHTML = "Total Users: " + data.total_users;
            document.getElementById("total-posts").innerHTML = "Total Posts: " + data.total_posts;
        }
    };
    xhr.open("GET", "get_stats.php", true);
    xhr.send();
}
function updateTasks() {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var data = JSON.parse(this.responseText);
            var ids = data.id;
            var tasks = data.task;
            var taskList = document.getElementById("task-list").getElementsByTagName('tbody')[0];

            // Clear existing tasks
            taskList.innerHTML = "";

            
            if (tasks.length === 0) {
                var row = taskList.insertRow(0);
                var cell = row.insertCell(0);
                cell.colSpan = 3;
                cell.innerHTML = "No new task";
                cell.classList.add("cell-center");
            } else {
                // Add fetched tasks to the list
                tasks.forEach(function(task, index) {
                    var row = taskList.insertRow(index);
                    var numCell = row.insertCell(0);
                    var taskCell = row.insertCell(1);
                    var actionCell = row.insertCell(2);

                    numCell.innerHTML = index + 1;
                    taskCell.innerHTML = task;
                    // Add the "Delete" button with the corresponding task ID
                    actionCell.innerHTML = "<button class='btn btn-danger delete-btn' data-id='" + ids[index] + "'>Delete</button>";
                });

                // Add event listeners to the "Delete" buttons
                var deleteBtns = document.getElementsByClassName("delete-btn");
                for (var i = 0; i < deleteBtns.length; i++) {
                    deleteBtns[i].addEventListener("click", function() {
                        var id = this.getAttribute("data-id");
                        deleteTask(id);
                    });
                }
            }
        }
    };
    xhr.open("GET", "get_tasks.php", true);
    xhr.send();
}


function addTask(task) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            updateTasks(); // Update tasks after adding a new task
        }
    };
    xhr.open("POST", "add_task.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("task=" + encodeURIComponent(task));
}

document.addEventListener("DOMContentLoaded", function() {
    updateStats();
    updateTasks();
    setInterval(updateStats, 5000); // Refreshes stats every 5 seconds
    setInterval(updateTasks, 5000); // Refreshes tasks every 5 seconds

    // Task form submission
    document.getElementById("task-form").addEventListener("submit", function(e) {
        e.preventDefault(); // Prevent default form submission behavior

        var newTask = document.getElementById("new-task");
        if (newTask.value.trim()) {
            addTask(newTask.value.trim());
            newTask.value = ""; // Clear the input field
        }
    });
});

function deleteTask(id) {
    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            updateTasks(); // Update tasks after deleting a task
        }
    };
    xhr.open("POST", "delete_task.php", true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhr.send("id=" + encodeURIComponent(id));
}
