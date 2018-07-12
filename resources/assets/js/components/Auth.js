import auth0 from 'auth0-js';
import history from './History'
import config from './Config'

export default class Auth {
    constructor() {
        this.auth0 = new auth0.WebAuth({
            domain: config.AUTH0_DOMAIN,
            clientID: config.AUTH0_FRONTEND_CLIENT_ID,
            redirectUri: config.APP_URL + 'callback',
            audience: config.APP_URL,
            responseType: 'token id_token',
            scope: 'openid profile email'
        });
    }

    login() {
        this.auth0.authorize();
    }

    handleAuthentication() {
        this.auth0.parseHash((err, authResult) => {
            if (authResult && authResult.accessToken && authResult.idToken) {
                this.setSession(authResult);
                this.storeAuth0Profile(authResult.idToken)
                    .then(() => history.replace('/'));
            } else if (err) {
                console.log(err);
            }
        });
    }

    storeAuth0Profile(idToken) {
        //console.log(profile);
        return fetch('/api/auth0user',
            {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    //'Authorization': `Bearer ${this.getAccessToken()}`
                },
                body: JSON.stringify({'idToken': idToken})
            });
    }

    setSession(authResult) {
        console.log("authResult", authResult);
        // Set the time that the Access Token will expire at
        let expiresAt = JSON.stringify((authResult.expiresIn * 1000) + new Date().getTime());
        localStorage.setItem('access_token', authResult.accessToken);
        localStorage.setItem('id_token', authResult.idToken);
        localStorage.setItem('expires_at', expiresAt);
        localStorage.setItem('profile', JSON.stringify(authResult.idTokenPayload));
        // navigate to the home route
        //history.replace('/');
    }

    logout() {
        // Clear Access Token and ID Token from local storage
        localStorage.removeItem('access_token');
        localStorage.removeItem('id_token');
        localStorage.removeItem('expires_at');
        localStorage.removeItem('profile');
        // navigate to the home route
        history.replace('/');
    }

    isAuthenticated() {
        // Check whether the current time is past the
        // Access Token's expiry time
        let expiresAt = JSON.parse(localStorage.getItem('expires_at'));
        return new Date().getTime() < expiresAt;
    }

    getAccessToken() {
        const accessToken = localStorage.getItem('access_token');
        if (!accessToken) {
            throw new Error('No Access Token found');
        }
        return accessToken;
    }

    getProfile() {
        // let accessToken = this.getAccessToken();
        // this.auth0.client.userInfo(accessToken, (err, profile) => {
        //     if (profile) {
        //         this.userProfile = profile;
        //     }
        //     cb(err, profile);
        // });
        const profile = localStorage.getItem('profile');
        if (!profile) {
            throw new Error('No profile found');
        }
        return JSON.parse(profile);
    }
}