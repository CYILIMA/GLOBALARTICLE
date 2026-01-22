function toggleMenu(){
  const menu = document.getElementById("mobileNav");
  menu.style.right = (menu.style.right === "0px") ? "-100%" : "0px";
}

function filterNews(category){
  const cards = document.querySelectorAll('.news-card');
  cards.forEach(card=>{
    card.style.display =
      category === 'all' || card.dataset.category === category
      ? 'block' : 'none';
  });
}

function goHome(){
  filterNews('all');
}

function subscribe(){
  const email = document.getElementById("email").value;
  if(email.includes("@")){
    alert("Subscribed successfully: " + email);
  } else {
    alert("Please enter a valid email");
  }
}

/* Breaking News Rotation */
const breaking = [
  "Breaking: Global events unfolding",
  "Breaking: Technology changing the world",
  "Breaking: Major international developments"
];

let i = 0;
setInterval(()=>{
  document.getElementById("breakingText").innerText = breaking[i];
  i = (i + 1) % breaking.length;
}, 3000);
