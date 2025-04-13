function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
        // Mortgage Calculator
        const propertyPrice = document.getElementById('propertyPrice');
        const initialPayment = document.getElementById('initialPayment');
        const loanTerm = document.getElementById('loanTerm');
        const interestRate = document.getElementById('interestRate');
        
        const priceValue = document.getElementById('priceValue');
        const initialPaymentValue = document.getElementById('initialPaymentValue');
        const loanTermValue = document.getElementById('loanTermValue');
        const interestRateValue = document.getElementById('interestRateValue');
        
        const loanAmount = document.getElementById('loanAmount');
        const monthlyPayment = document.getElementById('monthlyPayment');
        const totalInterest = document.getElementById('totalInterest');
        const totalPayment = document.getElementById('totalPayment');
        
        // Format number with spaces
        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, " ");
        }
        
        // Calculate mortgage
        function calculateMortgage() {
            const price = parseInt(propertyPrice.value);
            const downPayment = parseInt(initialPayment.value);
            const term = parseInt(loanTerm.value);
            const rate = parseFloat(interestRate.value);
            
            // Update displayed values
            priceValue.textContent = formatNumber(price) + ' ₽';
            initialPaymentValue.textContent = formatNumber(downPayment) + ' ₽';
            loanTermValue.textContent = term + ' лет';
            interestRateValue.textContent = rate + '%';
            
            // Calculate loan amount
            const loan = price - downPayment;
            loanAmount.textContent = formatNumber(loan) + ' ₽';
            
            // Calculate monthly payment
            const monthlyRate = rate / 100 / 12;
            const payments = term * 12;
            
            const x = Math.pow(1 + monthlyRate, payments);
            const monthly = (loan * x * monthlyRate) / (x - 1);
            
            if (isFinite(monthly)) {
                monthlyPayment.textContent = formatNumber(Math.round(monthly)) + ' ₽';
                totalInterest.textContent = formatNumber(Math.round((monthly * payments) - loan)) + ' ₽';
                totalPayment.textContent = formatNumber(Math.round(monthly * payments)) + ' ₽';
            } else {
                monthlyPayment.textContent = '0 ₽';
                totalInterest.textContent = '0 ₽';
                totalPayment.textContent = '0 ₽';
            }
        }
        
        // Event listeners for sliders
        propertyPrice.addEventListener('input', calculateMortgage);
        initialPayment.addEventListener('input', calculateMortgage);
        loanTerm.addEventListener('input', calculateMortgage);
        interestRate.addEventListener('input', calculateMortgage);
        
        // Initial calculation
        calculateMortgage();
        
        // Modal functionality
        
        const loginBtn = document.getElementById('loginBtn');
        const loginModal = document.getElementById('loginModal');
        const closeModal = document.querySelectorAll('.close-modal');
        const applyMortgageBtn = document.getElementById('applyMortgageBtn');
        const mortgageModal = document.getElementById('mortgageModal');
        
        loginBtn.addEventListener('click', function(e) {
            e.preventDefault();
            loginModal.style.display = 'flex';
           
        });
        
        applyMortgageBtn.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('mortgageAmount').value = loanAmount.textContent;
            document.getElementById('mortgageTerm').value = loanTerm.value + ' лет';
            mortgageModal.style.display = 'flex';
        });
        
        closeModal.forEach(btn => {
            btn.addEventListener('click', function() {
                loginModal.style.display = 'none';
                mortgageModal.style.display = 'none';
            });
        });
        
        window.addEventListener('click', function(e) {
            if (e.target === loginModal) {
                loginModal.style.display = 'none';
            }
            if (e.target === mortgageModal) {
                mortgageModal.style.display = 'none';
            }
        });
    
        // Form submissions
        
        
        document.getElementById('mortgageForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Заявка на ипотеку отправлена! Мы свяжемся с вами в ближайшее время.');
            mortgageModal.style.display = 'none';
        });
        
        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
       
 