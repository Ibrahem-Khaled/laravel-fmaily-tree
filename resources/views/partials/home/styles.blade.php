<style>
    /* ============================================================
                   SWIPER – Hero
                   ============================================================ */
    .heroSwiper {
        width: 100%;
        height: 100%;
    }

    .heroSwiper .swiper-slide {
        opacity: 0 !important;
        transition: opacity 0.8s ease-in-out;
    }

    .heroSwiper .swiper-slide-active {
        opacity: 1 !important;
    }

    .heroSwiper .swiper-slide img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .heroSwiper .swiper-button-next,
    .heroSwiper .swiper-button-prev {
        color: white;
        background: rgba(255, 255, 255, 0.2);
        backdrop-filter: blur(10px);
        width: 45px;
        height: 45px;
        border-radius: 50%;
        transition: all 0.3s ease;
    }

    .heroSwiper .swiper-button-next:hover,
    .heroSwiper .swiper-button-prev:hover {
        background: rgba(255, 255, 255, 0.35);
        transform: scale(1.15);
    }

    .heroSwiper .swiper-button-next::after,
    .heroSwiper .swiper-button-prev::after {
        font-size: 20px;
        font-weight: bold;
    }

    .heroSwiper .swiper-button-next {
        left: 20px;
        right: auto;
    }

    .heroSwiper .swiper-button-prev {
        right: 20px;
        left: auto;
    }

    .heroSwiper .swiper-pagination-bullet {
        background: rgba(255, 255, 255, 0.6);
        opacity: 1;
        width: 10px;
        height: 10px;
        transition: all 0.3s ease;
    }

    .heroSwiper .swiper-pagination-bullet-active {
        background: white;
        width: 30px;
        border-radius: 5px;
    }

    /* ============================================================
                   SWIPER – Gallery & Courses (shared)
                   ============================================================ */
    .gallerySwiper,
    .coursesSwiper {
        padding: 10px 35px 30px !important;
    }

    .gallerySwiper .swiper-button-next,
    .gallerySwiper .swiper-button-prev,
    .coursesSwiper .swiper-button-next,
    .coursesSwiper .swiper-button-prev {
        color: #37a05c;
        background: white;
        width: 45px;
        height: 45px;
        border-radius: 50%;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
        transition: all 0.3s ease;
    }

    .gallerySwiper .swiper-button-next:hover,
    .gallerySwiper .swiper-button-prev:hover,
    .coursesSwiper .swiper-button-next:hover,
    .coursesSwiper .swiper-button-prev:hover {
        background: #37a05c;
        color: white;
        box-shadow: 0 6px 20px rgba(55, 160, 92, 0.4);
        transform: scale(1.1);
    }

    .gallerySwiper .swiper-button-next::after,
    .gallerySwiper .swiper-button-prev::after,
    .coursesSwiper .swiper-button-next::after,
    .coursesSwiper .swiper-button-prev::after {
        font-size: 20px;
        font-weight: bold;
    }

    .gallerySwiper .swiper-button-next,
    .coursesSwiper .swiper-button-next {
        left: 0;
        right: auto;
    }

    .gallerySwiper .swiper-button-prev,
    .coursesSwiper .swiper-button-prev {
        right: 0;
        left: auto;
    }

    .gallerySwiper .swiper-pagination-bullet,
    .coursesSwiper .swiper-pagination-bullet {
        background: #d1d5db;
        opacity: 1;
        width: 10px;
        height: 10px;
        transition: all 0.3s ease;
    }

    .gallerySwiper .swiper-pagination-bullet-active,
    .coursesSwiper .swiper-pagination-bullet-active {
        background: #37a05c;
        width: 30px;
        border-radius: 5px;
    }

    /* ============================================================
                   Gallery Modal Animations
                   ============================================================ */
    #galleryModal {
        animation: fadeIn 0.3s ease-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    #galleryModalImageContainer,
    #galleryModalVideoContainer {
        animation: zoomIn 0.3s ease-out;
    }

    @keyframes zoomIn {
        from {
            opacity: 0;
            transform: scale(0.9);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    #galleryModalInfo {
        animation: slideUp 0.3s ease-out;
    }

    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulse-soft {

        0%,
        100% {
            transform: scale(1);
        }

        50% {
            transform: scale(1.03);
        }
    }

    /* Rich-text styles (.quiz-description, .question-text, .rich-content)
       live in resources/views/partials/rich-content-styles.blade.php and
       are included once at the bottom of this view. */

    /* ============================================================
                   Responsive overrides
                   ============================================================ */
    @media (max-width: 768px) {

        .heroSwiper .swiper-button-next,
        .heroSwiper .swiper-button-prev {
            width: 35px;
            height: 35px;
        }

        .heroSwiper .swiper-button-next::after,
        .heroSwiper .swiper-button-prev::after {
            font-size: 16px;
        }

        .heroSwiper .swiper-button-next {
            left: 10px;
        }

        .heroSwiper .swiper-button-prev {
            right: 10px;
        }

        .person-name-overlay {
            opacity: 1 !important;
        }

        .gallerySwiper,
        .coursesSwiper {
            padding: 8px 30px 25px !important;
        }

        .gallerySwiper .swiper-button-next,
        .gallerySwiper .swiper-button-prev,
        .coursesSwiper .swiper-button-next,
        .coursesSwiper .swiper-button-prev {
            width: 35px;
            height: 35px;
        }

        .gallerySwiper .swiper-button-next::after,
        .gallerySwiper .swiper-button-prev::after,
        .coursesSwiper .swiper-button-next::after,
        .coursesSwiper .swiper-button-prev::after {
            font-size: 16px;
        }
    }
</style>
