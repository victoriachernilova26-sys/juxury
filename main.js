document.addEventListener('DOMContentLoaded', function() {
    // 1. Вземаме данните за потребителя (симулираме, че сме ги записали при Login)
    // Примерна структура на записа в localStorage: { "username": "Ivan", "role": "admin" }
    const currentUser = JSON.parse(localStorage.getItem('juxury_user'));

    if (currentUser) {
        const adminBtn = document.getElementById('admin-btn');
        const vipStatus = document.getElementById('vip-status');

        // Проверка за АДМИН
        if (currentUser.role === 'admin') {
            if (adminBtn) adminBtn.style.display = 'inline-flex';
        } 
        
        // Проверка за VIP
        if (currentUser.role === 'vip' || currentUser.role === 'admin') {
            if (vipStatus) vipStatus.style.display = 'inline-flex';
            
            // Отключване на платени статии (ако имат клас .premium-content)
            document.querySelectorAll('.premium-content').forEach(el => {
                el.classList.remove('locked');
                el.innerHTML = "🔓 Платено съдържание (Отключено)";
            });
        }
    }
});

document.addEventListener('DOMContentLoaded', function() {
    
   
    // Селектори за всички кутии (статии), които ще филтрираме:
    // 1. .hero-section (Главната статия с голямата снимка горе)
    // 2. .article-card (Квадратните статии по средата и тези за Street Style)
    // 3. .street-grid-item (Runway статиите най-отдолу)
    const allArticles = document.querySelectorAll('.hero-section, .article-card, .street-grid-item');

    // ==========================================
    // 1. ФИЛТРИРАНЕ ПО КАТЕГОРИЯ
    // ==========================================
    
    const filterLinks = document.querySelectorAll('#filterMenu a');
    const filterMenu = document.getElementById('filterMenu');

    filterLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault(); 
            
            const selectedCategory = this.textContent.trim().toLowerCase();
            
            // Затваряне на менюто след клик
            if (filterMenu) filterMenu.classList.remove('show');

            allArticles.forEach(article => {
                // Намираме етикета с категорията в текущата статия (търси във всички твои класове)
                const categoryTag = article.querySelector('.hero-category, .article-category,  .article-card');
                
                let isMatch = false;
                
                if (categoryTag) {
                    const articleCategory = categoryTag.textContent.trim().toLowerCase();
                    // Проверява дали категорията съвпада с тази от линка
                    if (selectedCategory === 'всички' || articleCategory.includes(selectedCategory)) {
                        isMatch = true;
                    }
                } else if (selectedCategory === 'всички') {
                    isMatch = true;
                }

                // Скрива или показва статията
                if (isMatch) {
                    article.style.display = ''; // Връща нормалния изглед
                } else {
                    article.style.display = 'none'; // Скрива я, за да не заема място
                }
            });
        });
    });

    // ==========================================
    // 2. ТЪРСАЧКА
    // ==========================================
    // Намираме формата за търсене и полето вътре в нея
    const searchForm = document.querySelector('#searchBar form');
    const searchInput = document.querySelector('#searchBar input[name="q"]'); // Хващаме точно твоето input поле

    function performSearch() {
        if (!searchInput) return;
        
        const query = searchInput.value.trim().toLowerCase();

        allArticles.forEach(article => {
            // Намираме заглавието на статията
            const titleTag = article.querySelector('h1, .article-title, .runway-slide-subtitle, .article-card');
            
            let isMatch = false;
            if (titleTag) {
                const titleText = titleTag.textContent.trim().toLowerCase();
                // Проверява дали написаното в търсачката се съдържа в заглавието
                if (titleText.includes(query)) {
                    isMatch = true;
                }
            }
            
            // Ако търсачката е празна, показваме всичко. Иначе само съвпаденията.
            if (query === '' || isMatch) {
                article.style.display = '';
            } else {
                article.style.display = 'none';
            }
        });
    }

    // Най-важното: спираме формата да презарежда страницата при натискане на Enter!
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault(); 
            performSearch();
        });
    }

    // Филтрира на живо, буква по буква
    if (searchInput) {
        searchInput.addEventListener('input', performSearch);
    }
});