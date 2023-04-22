//Runs the logout.php script which unsets the users session, logging them out
function logout() {
  let xhr = new XMLHttpRequest();
  xhr.open("POST", "./actions/logout.php", true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.onreadystatechange = function () {
    if (this.readyState == 4 && this.status == 200) {
      window.location.replace("index.php"); // redirect to project page
    }
  };
  xhr.send();
}

let userId; //Session used id
let nameSearch; //Value of search input
let dateSearch; //Value of date input
let owned = false; //Value of owned checkbox

function isOwned() {
  owned = !owned;
  search();
}

//if the page is index.php sets event listeners and sets the userId session variable
window.onload = () => {
  if (
    window.location.pathname === "/index.php" ||
    window.location.pathname === "/"
  ) {
    dateSearch = document.querySelector("#date");
    nameSearch = document.querySelector("#search");

    dateSearch.addEventListener("input", () => {
      search();
    });
    nameSearch.addEventListener("input", () => {
      search();
    });

    const xhr = new XMLHttpRequest();

    xhr.open("GET", "./actions/getSessionVariable.php");

    xhr.onload = () => {
      userId = xhr.responseText;
      search(); //When screen loads displays all projects
    };
    xhr.onerror = () => console.log(xhr.status);
    xhr.send();
  }
};

//Runs the search.php script and sends the resulting array of objects to displaySearch function
function search() {
  try {
    const xhr = new XMLHttpRequest();

    xhr.onload = function () {
      const data = JSON.parse(xhr.responseText);
      displaySearch(data);
    };
    xhr.open(
      "GET",
      "./actions/search.php? nameSearch=" +
        encodeURIComponent(nameSearch.value) +
        "&dateSearch=" +
        encodeURIComponent(dateSearch.value) +
        "&userId=" +
        encodeURIComponent(userId) +
        "&owned=" +
        encodeURIComponent(owned)
    );

    xhr.send();
  } catch (error) {
    console.log(error);
  }
}

//Runs a foreach loop over array of objects and generates a card for each one that is appended to the container
function displaySearch(data) {
  const projectRow = document.querySelector("#project-row");
  projectRow.innerHTML = "";
  if (data.length == 0) {
    projectRow.innerHTML = "There are no projects that fit the search filter";
  }
  data.forEach((project) => {
    const column = document.createElement("div");
    column.classList.add("col-md-4");
    column.innerHTML = `
      <div class="card my-3" id="project" onclick="overlaySearch(event,this.getAttribute('data-item'))" data-item="${
        project["pid"]
      }">
        <div class="card-body text-center h5">
          ${project["title"]}
          <div class="text-secondary text-truncate mt-2 f-size">
            ${project["description"]}
          </div>
          <div class="mt-2">
            ${new Date(project["start_date"]).toLocaleDateString()}
          </div>
          <div class="mt-2 mh-25">
          ${
            project["uid"] == userId
              ? `
                  <a href="updateProject.php?name=${project["title"]}&startDate=${project["start_date"]}&endDate=${project["end-date"]}&phase=${project["phase"]}&body=${project["description"]}&projectId=${project["pid"]}">
                    <img id="update" src="image/edit.svg" style="max-width: 20px;">
                  </a>
                `
              : ""
          }
          </div>
        </div>
      </div>
    `;
    projectRow.appendChild(column);
  });
}

function overlaySearch(event, pid) {
  //When a project card is clicked it will send a request to the overlaySearch.php page
  //Which returns a project as a json object to be formatted inside the elements
  const title = document.querySelector("#title");
  const phase = document.querySelector("#phase");
  const description = document.querySelector("#description");
  const startDate = document.querySelector("#start-date");
  const endDate = document.querySelector("#end-date");
  const user = document.querySelector("#user");
  const userEmail = document.querySelector("#user-email");

  const xhr = new XMLHttpRequest();
  xhr.onload = function () {
    const data = JSON.parse(xhr.responseText);

    title.innerHTML = data[0]["title"];
    phase.innerHTML = data[0]["phase"];
    description.innerHTML = data[0]["description"];
    startDate.innerHTML = data[0]["start_date"];
    endDate.innerHTML = data[0]["end_date"];
    user.innerHTML = data[0]["username"];
    userEmail.innerHTML = data[0]["email"];

    overlayToggle(event);
  };

  xhr.open(
    "GET",
    "./actions/overlaySearch.php?projectId=" + encodeURIComponent(pid)
  );

  xhr.send();
}

function overlayToggle(event) {
  //Hides the overlay on user click
  if (event.target.id != "update") {
    const overlay = document.querySelector(".overlay");
    overlay.toggleAttribute("hidden");
  }
}
