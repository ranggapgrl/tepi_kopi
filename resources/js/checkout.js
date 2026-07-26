document.addEventListener('alpine:init', () => {
    Alpine.data('checkoutForm', (initialBaseTotal, totalWeightGram) => ({
        subtotal: initialBaseTotal,
        loading: false,
        errorList: [],
        // ... copy all methods from the inline function
    }));
}); 