removeUrlParameter('error_message')

function removeUrlParameter(paramName) {
    const url = new URL(window.location.href);
    if (url.searchParams.has(paramName)) {
        url.searchParams.delete(paramName); // Usunięcie parametru o podanej nazwie
        window.history.replaceState({}, document.title, url.toString()); // Aktualizacja URL bez odświeżania
    }
}