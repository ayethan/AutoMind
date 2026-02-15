export function formatDate(dateString) {
    if (!dateString) return '';
    const date = new Date(dateString);
    return date.toLocaleDateString(); // Or any desired date formatting
}

export function formatCurrency(value) {
    if (typeof value !== 'number') return value;
    return value.toLocaleString('en-US', {
        style: 'currency',
        currency: 'USD' // Assuming USD, adjust as needed
    });
}
