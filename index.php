<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Del Pueblo Mexican Restaurant Menu</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
      /* Custom transition for accordion content */
      .accordion-content {
        transition: max-height 0.5s ease-in-out, opacity 0.5s ease-in-out;
        overflow: hidden;
        max-height: 0; /* Initially hidden */
        opacity: 0; /* Initially hidden */
      }
      .accordion-content.show {
        opacity: 1; /* Make visible */
      }
    </style>
  </head>
  <body class="bg-gray-100 flex items-center justify-center px-4 py-8">
    <div class="container mx-auto px-4">
      <div class="flex items-center justify-center flex-col gap-2">
        <img src="/ntouch/assets/logo.png" alt="Del Pueblo Mexican Restaurant" />
        <h1 class="text-3xl font-bold mb-6 text-center">
          Del Pueblo Mexican Restaurant Menu
        </h1>
      </div>
      <div id="loading" class="flex items-center justify-center h-48">
        <div class="flex items-center space-x-2">
          <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-blue-600"></div>
          <span class="text-lg text-gray-600">Loading menu...</span>
        </div>
      </div>
      <div id="accordion" class="bg-white w-full mx-auto max-w-6xl rounded-lg shadow-md p-6 hidden"></div>
    </div>

    <script>
      // Function to fetch the menu data
      async function fetchMenu() {
        try {
          const response = await fetch("api-menu.php"); // Update this to your PHP endpoint
          if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
          }
          const data = await response.json();
          displayMenu(data); // Call function to display menu
        } catch (error) {
          console.error("Fetch error:", error);
          document.getElementById("loading").innerHTML = `
            <span class="text-red-600">Failed to load menu. Please try again later.</span>
          `;
        } finally {
          document.getElementById("loading").classList.add("hidden");
          document.getElementById("accordion").classList.remove("hidden");
        }
      }

      // Function to display the menu in an accordion format
      function displayMenu(data) {
        const accordionDiv = document.getElementById("accordion");
        accordionDiv.innerHTML = ""; // Clear previous content

        data.forEach((menu) => {
          const categoryDiv = document.createElement("div");
          categoryDiv.className = "mb-4 rounded-lg border border-gray-300 overflow-hidden";

          // Create the accordion header
          const header = document.createElement("button");
          header.className = "w-full text-left px-4 py-3 bg-gray-200 hover:bg-gray-300 focus:outline-none focus:ring focus:ring-blue-300 transition duration-200 ease-in-out";
          header.innerText = menu.name;
          header.setAttribute("aria-expanded", "false");
          header.setAttribute("aria-controls", `content-${menu.id}`);

          header.onclick = () => {
            const content = document.getElementById(`content-${menu.id}`);
            const isOpen = content.classList.toggle("show");
            header.setAttribute("aria-expanded", isOpen);
            content.style.maxHeight = isOpen ? `${content.scrollHeight}px` : "0"; // Expand/collapse
          };

          categoryDiv.appendChild(header);

          // Create the accordion content
          const contentDiv = document.createElement("div");
          contentDiv.id = `content-${menu.id}`;
          contentDiv.className = "accordion-content";

          menu.categories.forEach((category) => {
            // Create category header
            const categoryHeader = document.createElement("h3");
            categoryHeader.className = "font-semibold mt-2 text-lg text-gray-800 w-full block px-4 py-2 bg-gray-100";
            categoryHeader.innerText = category.name;
            contentDiv.appendChild(categoryHeader);

            // Grid for items
            const itemGrid = document.createElement("div");
            itemGrid.className = "grid grid-cols-1 md:grid-cols-2 gap-4 p-4";

            category.items.forEach((item) => {
              const itemDiv = document.createElement("div");
              itemDiv.className = "bg-gray-100 p-4 rounded-lg shadow-sm flex justify-between items-center";

              // Item details
              const detailsDiv = document.createElement("div");
              detailsDiv.className = "flex-1";
              const itemPrice = item.prices.length > 0 ? (item.prices[0].value / 100).toFixed(2) : "N/A";

              detailsDiv.innerHTML = `
                <strong class="text-gray-900">${item.name}</strong>
                <span class="text-green-600 font-semibold float-right">$${itemPrice}</span>
                <p class="text-gray-600">${item.description}</p>
              `;

              // Like button
              const likeButton = document.createElement("button");
              likeButton.className = "ml-4 text-blue-500 hover:text-blue-700 focus:outline-none";
              likeButton.innerHTML = "❤️"; // Heart icon
              likeButton.onclick = () => {
                alert(`Liked ${item.name}!`);
              };

              itemDiv.appendChild(detailsDiv);
              itemDiv.appendChild(likeButton);
              itemGrid.appendChild(itemDiv);
            });

            contentDiv.appendChild(itemGrid);
          });

          categoryDiv.appendChild(contentDiv);
          accordionDiv.appendChild(categoryDiv);
        });
      }

      // Fetch menu data when the page loads
      window.onload = fetchMenu;
    </script>
  </body>
</html>

