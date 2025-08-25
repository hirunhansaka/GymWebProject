document.addEventListener("DOMContentLoaded", function () {
  // Mobile menu toggle
  const menuToggle = document.querySelector(".menu-toggle");
  const navLinks = document.querySelector(".nav-links");
  const backBtn = document.querySelector(".back-btn");

  menuToggle.addEventListener("click", () => {
    navLinks.classList.toggle("active");
  });

  // Show back button on mobile when not on homepage
  if (
    window.location.pathname.split("/").pop() !== "index.php" &&
    window.location.pathname !== "/"
  ) {
    backBtn.style.display = "block";
  }

  // Close mobile menu when clicking a link
  document.querySelectorAll(".nav-links a").forEach((link) => {
    link.addEventListener("click", () => {
      navLinks.classList.remove("active");
    });
  });

  // Highlight current page in navigation
  const currentPage = location.pathname.split("/").pop();
  document.querySelectorAll(".nav-links a").forEach((link) => {
    if (link.getAttribute("href") === currentPage) {
      link.classList.add("active");
    }
  });

  // BMI Calculator
  const calculateBtn = document.getElementById("calculate");
  if (calculateBtn) {
    calculateBtn.addEventListener("click", calculateBMI);
  }

  function calculateBMI() {
    const height = parseFloat(document.getElementById("height").value) / 100; // convert to meters
    const weight = parseFloat(document.getElementById("weight").value);
    const result = document.getElementById("bmi-result");
    const category = document.getElementById("bmi-category");

    if (height && weight) {
      const bmi = (weight / (height * height)).toFixed(1);
      result.textContent = bmi;

      // Animate the result
      result.style.display = "inline-block";
      result.style.animation = "bounce 0.5s";
      setTimeout(() => {
        result.style.animation = "";
      }, 500);

      if (bmi < 18.5) {
        category.textContent = "Underweight";
      } else if (bmi >= 18.5 && bmi <= 24.9) {
        category.textContent = "Normal Weight";
      } else if (bmi >= 25 && bmi <= 29.9) {
        category.textContent = "Overweight";
      } else {
        category.textContent = "Obese";
      }
    } else {
      alert("Please enter valid height and weight values");
    }
  }

  // Add scroll animation to sections
  const sections = document.querySelectorAll("section");

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = 1;
          entry.target.style.transform = "translateY(0)";
        }
      });
    },
    { threshold: 0.1 }
  );

  sections.forEach((section) => {
    section.style.opacity = 0;
    section.style.transform = "translateY(20px)";
    section.style.transition = "all 0.6s ease-out";
    observer.observe(section);
  });

  // Navbar scroll effect
  window.addEventListener("scroll", () => {
    if (window.scrollY > 50) {
      document.querySelector("nav").style.background = "rgba(0, 0, 0, 0.95)";
      document.querySelector("nav").style.padding = "15px 20px";
    } else {
      document.querySelector("nav").style.background = "rgba(0, 0, 0, 0.9)";
      document.querySelector("nav").style.padding = "20px";
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  console.log("FitZone Website Loaded!");

  // Smooth Scroll for Links
  document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
    anchor.addEventListener("click", function (e) {
      e.preventDefault();
      document.querySelector(this.getAttribute("href")).scrollIntoView({
        behavior: "smooth",
      });
    });
  });

  // Scroll Animation for Login Section
  const fadeElements = document.querySelectorAll(".fade-in");
  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = 1;
          entry.target.style.transform = "translateY(0)";
        }
      });
    },
    { threshold: 0.2 }
  );

  fadeElements.forEach((el) => {
    el.style.opacity = 0;
    el.style.transform = "translateY(20px)";
    observer.observe(el);
  });

  // Toggle Password Visibility
  const togglePassword = document.querySelector(".toggle-password");
  const passwordInput = document.getElementById("password");

  togglePassword.addEventListener("click", function () {
    if (passwordInput.type === "password") {
      passwordInput.type = "text";
      togglePassword.innerHTML = '<i class="fas fa-eye-slash"></i>';
    } else {
      passwordInput.type = "password";
      togglePassword.innerHTML = '<i class="fas fa-eye"></i>';
    }
  });
});

document.addEventListener("DOMContentLoaded", function () {
  // Class filtering functionality
  const filterButtons = document.querySelectorAll(".filter-btn");
  const classSlots = document.querySelectorAll(".class-slot");

  filterButtons.forEach((button) => {
    button.addEventListener("click", function () {
      // Remove active class from all buttons
      filterButtons.forEach((btn) => {
        btn.classList.remove("active");
      });

      // Add active class to clicked button
      this.classList.add("active");

      const filter = this.dataset.filter;

      // Show/hide classes based on filter
      classSlots.forEach((slot) => {
        if (filter === "all" || slot.dataset.category === filter) {
          slot.style.display = "block";
          setTimeout(() => {
            slot.style.opacity = "1";
            slot.style.transform = "translateY(0)";
          }, 50);
        } else {
          slot.style.opacity = "0";
          slot.style.transform = "translateY(10px)";
          setTimeout(() => {
            slot.style.display = "none";
          }, 300);
        }
      });
    });
  });

  // Highlight today's column
  const days = [
    "Sunday",
    "Monday",
    "Tuesday",
    "Wednesday",
    "Thursday",
    "Friday",
    "Saturday",
  ];
  const today = new Date().getDay();
  const dayHeaders = document.querySelectorAll(".day-header");

  dayHeaders.forEach((header, index) => {
    if (index === today) {
      header.classList.add("today");
    }
  });

  // Class slot click handler
  classSlots.forEach((slot) => {
    slot.addEventListener("click", function () {
      const className = this.querySelector("h3").textContent;
      const trainer = this.querySelector(
        ".class-meta span:nth-child(2)"
      ).textContent;
      const time = this.parentElement.querySelector(".time-slot").textContent;
      const day =
        this.closest(".day-column").querySelector(".day-header").textContent;

      // In a real implementation, this would open a booking modal
      console.log(
        `Selected: ${className} with ${trainer} at ${time} on ${day}`
      );
    });
  });
});
