window.addEventListener('load', () => {
    // Animasi untuk memisahkan background dari tengah
    gsap.to(".left", { duration: 1, x: "-100%", ease: "power2.out" });
    gsap.to(".right", { duration: 1, x: "100%", ease: "power2.out", onComplete: () => {
      document.getElementById("loading-screen").style.display = "none";
      document.getElementById("content").style.display = "block";
      gsap.from("#content", { duration: 1, opacity: 0, y: 50 });
    }});
  });