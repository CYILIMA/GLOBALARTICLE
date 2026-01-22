let newsData = [];
let breakingNews = [];
let breakingIndex = 0;

document.addEventListener('DOMContentLoaded', () => {
  loadNews();
  loadSidebars();
});

// FETCH NEWS FROM PHP
function loadNews() {
  console.log('Loading news...');
  fetch('news_api.php')
    .then(res => {
      console.log('Response status:', res.status);
      return res.json();
    })
    .then(data => {
      console.log('Data received:', data);
      newsData = data.newsData;
      breakingNews = data.breakingNews;

      console.log('News data:', newsData.length, 'articles');
      console.log('Breaking news:', breakingNews.length, 'articles');

      displayNews();
      startBreakingNewsRotation();
    })
    .catch(err => {
      console.error('Error fetching news:', err);
      // Show error message to user
      const container = document.getElementById('newsArea');
      if (container) {
        container.innerHTML = '<div style="color: red; padding: 20px;">Error loading news. Please check console for details.</div>';
      }
    });
}

// DISPLAY NEWS
function displayNews(category = 'all') {
  const container = document.getElementById('newsArea');
  if (!container) return;

  container.innerHTML = '';

  newsData.forEach(news => {
    if(category !== 'all' && news.category !== category) return;

    const card = document.createElement('div');
    card.className = 'news-card';
    card.dataset.category = news.category;

    card.innerHTML = `
      ${news.image ? `<img src="${news.image}" alt="${news.title}">` : ''}
      <div class="news-content">
        <span class="category">${news.category}</span>
        <h2>${news.title}</h2>
        <p>${news.content}</p>
        <small>${news.created_at}</small>
      </div>
    `;
    container.appendChild(card);
  });
}

// FILTER NEWS
function filterNews(category){
  displayNews(category);
}

// BREAKING NEWS ROTATION
function startBreakingNewsRotation(){
  if(breakingNews.length === 0) return;

  const img = document.getElementById('breakingImg');
  const title = document.getElementById('breakingTitle');

  setInterval(() => {
    if(!img || !title) return;

    const newsItem = breakingNews[breakingIndex];
    img.src = newsItem.image || '';
    title.innerText = newsItem.title;

    breakingIndex = (breakingIndex + 1) % breakingNews.length;
  }, 3000);
}

// LOAD SIDEBARS (optional)
function loadSidebars(){
  document.getElementById('topNews')?.innerHTML = '';
  document.getElementById('latestNews')?.innerHTML = '';
}

// SUBSCRIBE
function subscribe(){
  const email = document.getElementById('email')?.value.trim();
  if(email && email.includes('@') && email.includes('.')){
    alert('Subscribed successfully!');
    document.getElementById('email').value = '';
  } else alert('Enter valid email');
}

// MOBILE MENU
function toggleMenu(){
  const mobileNav = document.getElementById('mobileNav');
  if(!mobileNav) return;
  mobileNav.style.right = mobileNav.style.right === '0px' ? '-100%' : '0';
}

// HOME CLICK
function goHome(){
  displayNews('all');
}
