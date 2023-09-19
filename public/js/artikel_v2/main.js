/**
 * Template Name: Nova
 * Updated: Sep 18 2023 with Bootstrap v5.3.2
 * Template URL: https://bootstrapmade.com/nova-bootstrap-business-template/
 * Author: BootstrapMade.com
 * License: https://bootstrapmade.com/license/
 */
document.addEventListener("DOMContentLoaded", () => {
	"use strict";

	/**
	 * Preloader
	 */
	const preloader = document.querySelector("#preloader");
	if (preloader) {
		window.addEventListener("load", () => {
			preloader.remove();
		});
	}

	/**
	 * Sticky header on scroll
	 */
	const selectHeader = document.querySelector("#header");
	if (selectHeader) {
		document.addEventListener("scroll", () => {
			window.scrollY > 100
				? selectHeader.classList.add("sticked")
				: selectHeader.classList.remove("sticked");
		});
	}

	/**
	 * Mobile nav toggle
	 */
	const mobileNavShow = document.querySelector(".mobile-nav-show");
	const mobileNavHide = document.querySelector(".mobile-nav-hide");

	document.querySelectorAll(".mobile-nav-toggle").forEach((el) => {
		el.addEventListener("click", function (event) {
			event.preventDefault();
			mobileNavToogle();
		});
	});

	function mobileNavToogle() {
		document.querySelector("body").classList.toggle("mobile-nav-active");
		mobileNavShow.classList.toggle("d-none");
		mobileNavHide.classList.toggle("d-none");
	}

	/**
	 * Toggle mobile nav dropdowns
	 */
	const navDropdowns = document.querySelectorAll(".navbar .dropdown > a");

	navDropdowns.forEach((el) => {
		el.addEventListener("click", function (event) {
			if (document.querySelector(".mobile-nav-active")) {
				event.preventDefault();
				this.classList.toggle("active");
				this.nextElementSibling.classList.toggle("dropdown-active");

				let dropDownIndicator = this.querySelector(".dropdown-indicator");
				dropDownIndicator.classList.toggle("bi-chevron-up");
				dropDownIndicator.classList.toggle("bi-chevron-down");
			}
		});
	});

	/**
	 * Scroll top button
	 */
	const scrollTop = document.querySelector(".scroll-top");
	if (scrollTop) {
		const togglescrollTop = function () {
			window.scrollY > 100
				? scrollTop.classList.add("active")
				: scrollTop.classList.remove("active");
		};
		window.addEventListener("load", togglescrollTop);
		document.addEventListener("scroll", togglescrollTop);
		scrollTop.addEventListener(
			"click",
			window.scrollTo({
				top: 0,
				behavior: "smooth",
			})
		);
	}

	/**
	 * Animation on scroll function and init
	 */
	function aos_init() {
		AOS.init({
			duration: 800,
			easing: "slide",
			once: true,
			mirror: false,
		});
	}
	window.addEventListener("load", () => {
		aos_init();
	});
});
