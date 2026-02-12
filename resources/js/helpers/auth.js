export function login(credentials) {
    return new Promise((resolve, reject) => {
        axios.post('/api/auth/login', credentials)
            .then((response) => {
                setAuthenticationHeader(response.data.access_token);
                resolve(response.data);
            })
            .catch((err) =>{
                reject("Credentials do not match");
            })
    });
}

export function setAuthenticationHeader(access_token) {
    axios.defaults.headers.common["Authorization"] = `Bearer ${access_token}`
}

export function getUser() {
    const user_str = localStorage.getItem("user");

    if(!user_str) {
        return null;
    }

    return JSON.parse(user_str);
}

export function logout() {
    return new Promise((resolve, reject) => {
        axios.get('/api/auth/logout')
            .then((response) => {
                resolve(response.data);
            })
            .catch((error) => {
                reject("Cannot log out");
            });
    });
}