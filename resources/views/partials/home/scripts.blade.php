<!-- SortableJS for Drag and Drop Ordering -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
    /* ================================================================
       SWIPERS
       ================================================================ */
    document.addEventListener('DOMContentLoaded', function() {
        var totalSlides = {{ $latestImages->count() ?? 0 }};
        var totalImages = {{ $latestGalleryImages->count() ?? 0 }};
        var totalCourses = {{ $courses->count() ?? 0 }};

        // Hero
        if (totalSlides > 0 && typeof Swiper !== 'undefined') {
            new Swiper('.heroSwiper', {
                slidesPerView: 1,
                loop: totalSlides > 1,
                autoplay: {
                    delay: 5000,
                    disableOnInteraction: false
                },
                effect: 'fade',
                fadeEffect: {
                    crossFade: true
                },
                speed: 1000,
                pagination: {
                    el: '.hero-pagination',
                    clickable: true,
                    dynamicBullets: true
                },
                navigation: {
                    nextEl: '.hero-next',
                    prevEl: '.hero-prev'
                },
                keyboard: {
                    enabled: true
                },
            });
        }

        // Gallery & Courses – wait one frame so Swiper gets correct dimensions
        var initSwipers = function() {
            if (totalImages > 0 && typeof Swiper !== 'undefined' && document.querySelector(
                    '.gallerySwiper')) {
                new Swiper('.gallerySwiper', {
                    slidesPerView: 2,
                    spaceBetween: 15,
                    loop: totalImages > 4,
                    autoplay: {
                        delay: 4000,
                        disableOnInteraction: false
                    },
                    pagination: {
                        el: '.gallery-pagination',
                        clickable: true,
                        dynamicBullets: true
                    },
                    navigation: {
                        nextEl: '.gallery-next',
                        prevEl: '.gallery-prev'
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: 3,
                            spaceBetween: 20
                        },
                        1024: {
                            slidesPerView: 4,
                            spaceBetween: 24
                        },
                        1280: {
                            slidesPerView: 5,
                            spaceBetween: 24
                        },
                    },
                });
            }
            if (totalCourses > 0 && typeof Swiper !== 'undefined' && document.querySelector(
                    '.coursesSwiper')) {
                new Swiper('.coursesSwiper', {
                    slidesPerView: 1,
                    spaceBetween: 20,
                    loop: totalCourses > 3,
                    autoplay: {
                        delay: 5000,
                        disableOnInteraction: false
                    },
                    pagination: {
                        el: '.courses-pagination',
                        clickable: true,
                        dynamicBullets: true
                    },
                    navigation: {
                        nextEl: '.courses-next',
                        prevEl: '.courses-prev'
                    },
                    breakpoints: {
                        640: {
                            slidesPerView: 2,
                            spaceBetween: 20
                        },
                        1024: {
                            slidesPerView: 3,
                            spaceBetween: 24
                        },
                    },
                });
            }
        };

        requestAnimationFrame
            ?
            requestAnimationFrame(function() {
                setTimeout(initSwipers, 50);
            }) :
            setTimeout(initSwipers, 100);
    });

    /* ================================================================
       ACCORDION – Councils & Events
       ================================================================ */
    function toggleAccordion(type, id) {
        var row = document.querySelector('.' + type + '-description-' + id);
        var content = row && row.querySelector('.' + type + '-description-content');
        var chevron = document.querySelector('.' + type + '-chevron-' + id);
        if (!row || !content) return;

        var isHidden = row.classList.contains('hidden');
        if (isHidden) {
            row.classList.remove('hidden');
            setTimeout(function() {
                content.style.maxHeight = content.scrollHeight + 'px';
            }, 10);
            if (chevron) chevron.style.transform = 'rotate(180deg)';
        } else {
            content.style.maxHeight = '0';
            if (chevron) chevron.style.transform = 'rotate(0deg)';
            setTimeout(function() {
                row.classList.add('hidden');
            }, 500);
        }
    }

    function toggleCouncilDescription(id) {
        toggleAccordion('council', id);
    }

    function toggleEventDescription(id) {
        var panel = document.querySelector('[data-event-desc-panel="' + id + '"]');
        var trigger = document.querySelector('[data-event-desc-trigger="' + id + '"]');
        var content = panel && panel.querySelector('.event-description-content');
        var chevron = document.querySelector('.event-chevron-' + id);
        if (!panel || !content) {
            return;
        }

        var isHidden = panel.classList.contains('hidden');
        if (isHidden) {
            panel.classList.remove('hidden');
            content.style.maxHeight = '0px';
            void panel.offsetHeight;
            requestAnimationFrame(function() {
                content.style.maxHeight = content.scrollHeight + 24 + 'px';
            });
            if (chevron) {
                chevron.style.transform = 'rotate(180deg)';
            }
            if (trigger) {
                trigger.setAttribute('aria-expanded', 'true');
            }
        } else {
            content.style.maxHeight = '0px';
            if (chevron) {
                chevron.style.transform = 'rotate(0deg)';
            }
            if (trigger) {
                trigger.setAttribute('aria-expanded', 'false');
            }
            setTimeout(function() {
                panel.classList.add('hidden');
            }, 500);
        }
    }

    /* ================================================================
       EVENTS – Day countdown
       ================================================================ */
    function updateEventCountdowns() {
        document.querySelectorAll('[class*="event-countdown-"]').forEach(function(el) {
            var dateStr = el.getAttribute('data-event-date');
            if (!dateStr) return;
            var diff = new Date(dateStr) - new Date();
            if (diff <= 0) {
                el.innerHTML = '<span class="text-red-600">انتهت المناسبة</span>';
                return;
            }
            var daysEl = el.querySelector('.countdown-days');
            if (daysEl) daysEl.textContent = Math.floor(diff / 86400000);
        });
    }
    updateEventCountdowns();
    setInterval(updateEventCountdowns, 60000);

    /* ================================================================
       GALLERY MODAL
       ================================================================ */
    var Gallery = (function() {
        var modal = document.getElementById('galleryModal');
        var imageWrapper = document.getElementById('galleryModalImageContainer');
        var videoWrapper = document.getElementById('galleryModalVideoContainer');
        var imgEl = document.getElementById('galleryModalImage');
        var iframeEl = document.getElementById('galleryModalVideo');
        var titleEl = document.getElementById('galleryModalTitle');
        var catEl = document.getElementById('galleryModalCategory');

        function extractYoutubeId(url) {
            var patterns = [
                /(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/,
                /youtube\.com\/watch\?.*v=([a-zA-Z0-9_-]{11})/,
            ];
            for (var i = 0; i < patterns.length; i++) {
                var m = url.match(patterns[i]);
                if (m) return m[1];
            }
            return null;
        }

        function open(item) {
            var type = item.getAttribute('data-media-type');
            var imgUrl = item.getAttribute('data-image-url');
            var ytUrl = item.getAttribute('data-youtube-url');
            var name = item.getAttribute('data-image-name');
            var category = item.getAttribute('data-category-name');

            titleEl.textContent = name;
            catEl.textContent = category || '';

            if (type === 'youtube' && ytUrl) {
                var vid = extractYoutubeId(ytUrl);
                if (vid) {
                    iframeEl.src = 'https://www.youtube.com/embed/' + vid + '?autoplay=1';
                    imageWrapper.classList.add('hidden');
                    videoWrapper.classList.remove('hidden');
                } else {
                    imgEl.src = imgUrl;
                    videoWrapper.classList.add('hidden');
                    imageWrapper.classList.remove('hidden');
                }
            } else {
                imgEl.src = imgUrl;
                videoWrapper.classList.add('hidden');
                iframeEl.src = '';
                imageWrapper.classList.remove('hidden');
            }

            modal.classList.remove('hidden');
            modal.style.display = 'flex';
            document.body.style.overflow = 'hidden';
        }

        function close() {
            modal.classList.add('hidden');
            modal.style.display = 'none';
            document.body.style.overflow = '';
            iframeEl.src = '';
            imageWrapper.classList.add('hidden');
            videoWrapper.classList.add('hidden');
        }

        // Bind events after DOM ready
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.gallery-item').forEach(function(item) {
                item.addEventListener('click', function() {
                    open(this);
                });
            });

            document.getElementById('closeGalleryModal')
                .addEventListener('click', close);

            modal.addEventListener('click', function(e) {
                if (e.target === modal) close();
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') close();
            });

            // Swipe-down to close
            var sy = 0;
            modal.addEventListener('touchstart', function(e) {
                sy = e.changedTouches[0].screenY;
            }, {
                passive: true
            });
            modal.addEventListener('touchend', function(e) {
                if (sy - e.changedTouches[0].screenY > 100) close();
            }, {
                passive: true
            });
        });

        return {
            open: open,
            close: close
        };
    })();

    /* ================================================================
       QUIZ – Next event countdown  (بدون reload – AJAX عند الانتهاء)
       ================================================================ */
    @if (isset($nextQuizEvent) && $nextQuizEvent)
        (function() {
            var el = document.getElementById('quizCountdownTarget');
            if (!el) return;

            var target = parseInt(el.value, 10);
            var ids = ['days', 'hours', 'minutes', 'seconds'];
            var finished = false;
            var timer;

            function zeroAll() {
                ids.forEach(function(id) {
                    var node = document.getElementById('countdown-' + id);
                    if (node) node.textContent = '0';
                });
            }

            /* ── جلب المسابقة النشطة بـ AJAX وحقنها بدون reload ── */
            function fetchActiveQuiz() {
                var wrapper = document.getElementById('quizCountdownSection');

                /* حالة تحميل مؤقتة */
                if (wrapper) {
                    wrapper.innerHTML =
                        '<div class="glass-card rounded-3xl p-6 text-center shadow-lg" style="box-shadow:0 0 30px rgba(34,197,94,.15);">' +
                        '<div class="inline-flex items-center gap-2 text-green-600 text-sm font-medium">' +
                        '<svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">' +
                        '<circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>' +
                        '<path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>' +
                        '</svg><span>جاري تحميل المسابقة...</span></div></div>';
                }

                fetch(window.location.href, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    })
                    .then(function(res) {
                        return res.text();
                    })
                    .then(function(html) {
                        var parser = new DOMParser();
                        var doc = parser.parseFromString(html, 'text/html');
                        var newActive = doc.getElementById('activeQuizSection');

                        if (newActive) {
                            /* المسابقة بدأت → استبدل قسم العداد بكتلة المسابقة (قد تحتوي أكثر من مسابقة) */
                            if (wrapper) wrapper.outerHTML = newActive.outerHTML;

                            /* شغّل عدادات نهاية كل مسابقة وعدادات كشف الأسئلة */
                            if (typeof startAllActiveQuizTimers === 'function') startAllActiveQuizTimers();
                            if (typeof startAllRevealCountdowns === 'function') startAllRevealCountdowns();
                        } else {
                            /* لم تبدأ بعد → إعادة المحاولة بعد 10 ثوانٍ */
                            var current = document.getElementById('quizCountdownSection');
                            if (current) {
                                current.innerHTML =
                                    '<div class="glass-card rounded-3xl p-4 text-center shadow-lg border-2 border-amber-200 bg-amber-50">' +
                                    '<i class="fas fa-clock text-amber-500 text-2xl mb-2 block"></i>' +
                                    '<p class="text-amber-800 font-medium text-sm">جاري تجهيز المسابقة، سيتم التحديث تلقائياً...</p>' +
                                    '</div>';
                            }
                            setTimeout(fetchActiveQuiz, 10000);
                        }
                    })
                    .catch(function() {
                        setTimeout(fetchActiveQuiz, 15000);
                    });
            }

            /* ── العداد الرئيسي ── */
            function tick() {
                var diff = target - Date.now();

                if (diff <= 0 && !finished) {
                    finished = true;
                    clearInterval(timer);
                    zeroAll();
                    setTimeout(fetchActiveQuiz, 500);
                    return;
                }

                var d = Math.floor(diff / 86400000);
                var h = Math.floor((diff % 86400000) / 3600000);
                var m = Math.floor((diff % 3600000) / 60000);
                var s = Math.floor((diff % 60000) / 1000);

                [d, h, m, s].forEach(function(val, i) {
                    var node = document.getElementById('countdown-' + ids[i]);
                    if (node) node.textContent = val;
                });
            }

            tick();
            timer = setInterval(tick, 1000);
        })();
    @endif

    /* ================================================================
       QUIZ – Active competition helpers
       (دوال global عشان AJAX يقدر يستدعيها بعد حقن HTML جديد)
       ================================================================ */

    /* عداد h:m:s لنهاية المسابقة الفعلية (suffix اختياري، مثال: '' أو '-5' لمسابقة id=5) */
    function startActiveQuizTimer(endTimestamp, suffix) {
        suffix = suffix || '';
        function pad(n) {
            return n.toString().padStart(2, '0');
        }

        function tick() {
            var diff = endTimestamp - Date.now();
            var hEl = document.getElementById('aq-hours' + suffix);
            var mEl = document.getElementById('aq-minutes' + suffix);
            var sEl = document.getElementById('aq-seconds' + suffix);
            if (!hEl) return; // العنصر اتشال من DOM
            if (diff <= 0) {
                hEl.textContent = '00';
                mEl.textContent = '00';
                sEl.textContent = '00';
                window.location.reload(); // انتهاء المسابقة الفعلية → reload
                return;
            }
            hEl.textContent = pad(Math.floor(diff / 3600000));
            mEl.textContent = pad(Math.floor((diff % 3600000) / 60000));
            sEl.textContent = pad(Math.floor((diff % 60000) / 1000));
        }
        tick();
        setInterval(tick, 1000);
    }

    /* عداد الثواني حتى ظهور نص الأسئلة (suffix اختياري) */
    function startRevealCountdown(visibleAtTimestamp, suffix) {
        suffix = suffix || '';
        var banner = document.getElementById('activeQuizQuestionsCountdown' + suffix);
        var descs = document.getElementById('activeQuizDescriptionsOnlyBlock' + suffix);
        var block = document.getElementById('activeQuizQuestionsBlock' + suffix);
        var display = document.getElementById('aqQuestionsSeconds' + suffix);
        if (!banner || !block || !display) return;

        function reveal() {
            banner.style.display = 'none';
            if (descs) descs.style.display = 'none';
            block.style.display = '';
        }

        var iv = setInterval(function() {
            var remaining = Math.ceil((visibleAtTimestamp - Date.now()) / 1000);
            if (remaining <= 0) {
                clearInterval(iv);
                display.textContent = '0';
                reveal(); // فقط كشف الأسئلة – بدون reload
            } else {
                display.textContent = remaining;
            }
        }, 1000);

        /* تشغيل فوري قبل أول tick */
        var init = Math.ceil((visibleAtTimestamp - Date.now()) / 1000);
        display.textContent = init > 0 ? init : '0';
        if (init <= 0) {
            clearInterval(iv);
            reveal();
        }
    }

    /* تشغيل كل عدادات المسابقات النشطة (عند التحميل أو بعد حقن AJAX) */
    function startAllActiveQuizTimers() {
        document.querySelectorAll('[id^="aqEndTime-"]').forEach(function(el) {
            var id = el.id;
            var suffix = id.indexOf('-') >= 0 ? id.substring(id.indexOf('-')) : '';
            startActiveQuizTimer(parseInt(el.value, 10), suffix);
        });
        /* توافق قديم: عنصر واحد بدون suffix */
        var legacyEnd = document.getElementById('aqEndTime');
        if (legacyEnd && !legacyEnd.id.match(/-/)) {
            startActiveQuizTimer(parseInt(legacyEnd.value, 10));
        }
    }
    function startAllRevealCountdowns() {
        document.querySelectorAll('[id^="aqQuestionsVisibleAt-"]').forEach(function(el) {
            var id = el.id;
            var suffix = id.indexOf('-') >= 0 ? id.substring(id.indexOf('-')) : '';
            startRevealCountdown(parseInt(el.value, 10), suffix);
        });
        var legacyVis = document.getElementById('aqQuestionsVisibleAt');
        if (legacyVis && !legacyVis.id.match(/-/)) {
            startRevealCountdown(parseInt(legacyVis.value, 10));
        }
    }

    /* تشغيل عند تحميل الصفحة لو مسابقة/مسابقات موجودة أصلاً من السيرفر */
    @if (isset($activeQuizCompetitions) && $activeQuizCompetitions->isNotEmpty())
        (function() {
            startAllActiveQuizTimers();
            startAllRevealCountdowns();
        })();
    @endif
    /* ================================================================
       QUIZ – Home Forms Javascript Checkbox Validation helpers
       ================================================================ */

    function validateHomeQuiz(event, buttonElement) {
        const form = buttonElement.closest('form');
        if (!form) return;

        const orderingTotal = form.querySelector('.ordering-total-count');
        if (orderingTotal) {
            const total = parseInt(orderingTotal.value);
            const placed = form.querySelectorAll('input[name="answer[]"].ordering-img-hidden').length;
            if (placed < total) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'تنبيه',
                    text: `يجب وضع جميع الصور (${total}) في المربعات قبل الإرسال.`,
                    confirmButtonColor: '#22c55e',
                    confirmButtonText: 'حسناً'
                });
                return false;
            }
        }

        const requiredCountInput = form.querySelector('.required-choices-count');
        if (requiredCountInput) {
            const requiredCount = parseInt(requiredCountInput.value);
            const checkedBoxes = form.querySelectorAll('input[type="checkbox"][name="answer[]"]:checked');

            if (checkedBoxes.length !== requiredCount) {
                event.preventDefault();
                Swal.fire({
                    icon: 'warning',
                    title: 'تنبيه',
                    text: `الرجاء اختيار عدد ${requiredCount} إجابات كما هو مطلوب بالسؤال.`,
                    confirmButtonColor: '#22c55e',
                    confirmButtonText: 'حسناً'
                });
                return false;
            }
        }

        return true;
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Checkbox limits constraint listener
        function attachCheckboxListeners() {
            const forms = document.querySelectorAll('.js-active-quiz-questions-block form, [id^="activeQuizQuestionsBlock-"] form');
            forms.forEach(form => {
                const requiredCountInput = form.querySelector('.required-choices-count');
                if (requiredCountInput) {
                    const requiredCount = parseInt(requiredCountInput.value);
                    const checkboxes = form.querySelectorAll('input[type="checkbox"][name="answer[]"]');

                    checkboxes.forEach(cb => {
                        // Only add if not already added
                        if (!cb.hasAttribute('data-listener-attached')) {
                            cb.addEventListener('change', function() {
                                const checkedCount = form.querySelectorAll(
                                    'input[type="checkbox"][name="answer[]"]:checked'
                                    ).length;
                                if (checkedCount > requiredCount) {
                                    this.checked = false;
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'تم تجاوز الحد الأقصى',
                                        text: `لا يمكنك اختيار أكثر من ${requiredCount} إجابات.`,
                                        confirmButtonColor: '#22c55e',
                                        confirmButtonText: 'حسناً',
                                        toast: true,
                                        position: 'top-end',
                                        showConfirmButton: false,
                                        timer: 3000
                                    });
                                }
                            });
                            cb.setAttribute('data-listener-attached', 'true');
                        }
                    });
                }
            });
        }

        // Run on load (يدعم أكثر من مسابقة: كل الكتل ذات الأسئلة)
        attachCheckboxListeners();

        function syncSlotStates(zone, sourceGrid) {
            if (!zone) return;
            var qId = zone.getAttribute('data-question-id');
            var input = document.getElementById('orderInput-' + qId);
            if (!input) return;

            var ids = [];
            // Query all slots across all grid containers in this zone
            zone.querySelectorAll('.ordering-slot').forEach(function(slot) {
                var img = slot.querySelector('.ordering-img-item');
                slot.classList.toggle('has-image', !!img);
                if (img) ids.push(img.getAttribute('data-id'));
            });
            input.value = ids.join(',');

            var parent = input.parentNode;
            parent.querySelectorAll('input.ordering-img-hidden').forEach(function(h) {
                h.remove();
            });
            ids.forEach(function(id) {
                var h = document.createElement('input');
                h.type = 'hidden';
                h.name = 'answer[]';
                h.value = id;
                h.className = 'ordering-img-hidden';
                parent.insertBefore(h, input);
            });
        }

        function initImageOrdering() {
            document.querySelectorAll('.ordering-source-grid:not(.sortable-initialized)').forEach(function(
                sourceGrid) {
                var qId = sourceGrid.closest('.ordering-source-zone').getAttribute('data-question-id');
                var targetZone = document.querySelector('.ordering-target-zone[data-question-id="' + qId + '"]');
                if (!targetZone) return;
                
                var groupName = 'imgOrder-' + qId;

                new Sortable(sourceGrid, {
                    group: {
                        name: groupName,
                        pull: 'clone',
                        put: true
                    },
                    animation: 150,
                    sort: false,
                    ghostClass: 'sortable-ghost',
                    chosenClass: 'sortable-chosen',
                    fallbackOnBody: true
                });

                targetZone.querySelectorAll('.ordering-slot').forEach(function(slot) {
                    if (slot.classList.contains('slot-init')) return;

                    new Sortable(slot, {
                        group: {
                            name: groupName,
                            put: function() {
                                return !slot.querySelector('.ordering-img-item');
                            }
                        },
                        animation: 150,
                        ghostClass: 'sortable-ghost',
                        fallbackOnBody: true,
                        onAdd: function(evt) {
                            if (evt.from === sourceGrid) {
                                var orig = sourceGrid.querySelector('[data-id="' +
                                    evt.item.getAttribute('data-id') + '"]');
                                if (orig && orig !== evt.item) orig.remove();
                            }
                            syncSlotStates(targetZone, sourceGrid);
                        },
                        onRemove: function(evt) {
                            if (evt.to !== sourceGrid) {
                                sourceGrid.appendChild(evt.item);
                            }
                            syncSlotStates(targetZone, sourceGrid);
                        }
                    });

                    slot.addEventListener('dragenter', function() {
                        slot.classList.add('drag-hover');
                    });
                    slot.addEventListener('dragleave', function() {
                        slot.classList.remove('drag-hover');
                    });
                    slot.addEventListener('drop', function() {
                        slot.classList.remove('drag-hover');
                    });

                    slot.classList.add('slot-init');
                });

                sourceGrid.classList.add('sortable-initialized');
            });
        }

        function initSortables() {
            var lists = document.querySelectorAll('.sortable-list:not(.sortable-initialized)');
            lists.forEach(function(list) {
                new Sortable(list, {
                    animation: 150,
                    ghostClass: 'bg-green-50',
                    chosenClass: 'sortable-chosen'
                });
                list.classList.add('sortable-initialized');
            });
            initImageOrdering();
        }
        initSortables();

        // Setup a MutationObserver to re-attach when countdown/active section is replaced (e.g. after AJAX)
        const observerTarget = document.getElementById('quizCountdownSection') || document.getElementById('activeQuizSection');
        if (observerTarget && observerTarget.parentNode) {
            const observer = new MutationObserver(function(mutations) {
                mutations.forEach(function(mutation) {
                    if (mutation.addedNodes.length > 0) {
                        attachCheckboxListeners();
                        initSortables();
                    }
                });
            });
            observer.observe(observerTarget.parentNode, {
                childList: true,
                subtree: true
            });
        }
    });
</script>
