async function loadNews() {
    let container = document.getElementById("news");
    container.innerHTML = `<div class="loader"></div>`;

    let category = document.getElementById("category").value;

    let res = await fetch(`api/fetch_news.php?category=${category}`);
    let data = await res.json();

    displayNews(data.articles || []);
}

function displayNews(articles) {
    let container = document.getElementById("news");
    container.innerHTML = "";

    if (articles.length === 0) {
        container.innerHTML = "<h2>No news found 😢</h2>";
        return;
    }

    articles.forEach(article => {
        let div = document.createElement("div");
        div.className = "card";

        let image = article.image || "https://via.placeholder.com/400x220?text=No+Image";
        let title = article.title || "No title";
        let description = article.description || "No description available";
        let url = article.url || "#";

        div.innerHTML = `
            <img src="${image}" alt="news image">
            <div class="card-content">
                <h3>${title}</h3>
                <p>${description}</p>
                <div class="card-actions">
                    <a href="${url}" target="_blank">Read More</a>
                    <button onclick='saveNews(${JSON.stringify({
                        title: title,
                        description: description,
                        url: url,
                        image: image
                    })})'>⭐ Save</button>
                </div>
            </div>
        `;

        container.appendChild(div);
    });
}

async function saveNews(article) {
    try {
        let res = await fetch("api/save_news.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(article)
        });

        let text = await res.text();
        console.log("Raw response:", text);

        let data = JSON.parse(text);
        alert(data.message);
    } catch (error) {
        console.error("Save error:", error);
        alert("Failed to save news");
    }
}

async function loadSaved() {
    try {
        let res = await fetch("api/get_saved.php");
        let data = await res.json();

        let container = document.getElementById("news");
        container.innerHTML = "";

        if (data.length === 0) {
            container.innerHTML = "<h2 class='empty-msg'>No saved news yet</h2>";
            return;
        }

        data.forEach(item => {
            let div = document.createElement("div");
            div.className = "card";

            div.innerHTML = `
                <img src="${item.image || 'https://via.placeholder.com/400x220?text=No+Image'}" alt="saved news image">
                <div class="card-content">
                    <h3>${item.title}</h3>
                    <p>${item.description || 'No description available'}</p>
                    <div class="card-actions">
                        <a href="${item.url}" target="_blank">Read</a>
                        <button onclick="deleteNews(${item.id})">❌ Delete</button>
                    </div>
                </div>
            `;

            container.appendChild(div);
        });
    } catch (error) {
        document.getElementById("news").innerHTML = "<p>Failed to load saved news.</p>";
        console.error(error);
    }
}

async function deleteNews(id) {
    try {
        let res = await fetch("api/delete_news.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({ id })
        });

        let data = await res.json();
        alert(data.message);
        loadSaved();
    } catch (error) {
        alert("Failed to delete news");
        console.error(error);
    }
}

window.onload = loadNews;