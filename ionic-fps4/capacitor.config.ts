import type { CapacitorConfig } from '@capacitor/cli';

const config: CapacitorConfig = {
  plugins: {
    OAuth2Client: {
      clientId: '<YOUR_GOOGLE_CLIENT_ID>',
      appId: '<YOUR_GOOGLE_CLIENT_ID>',
      authorizationBaseUrl: 'https://accounts.google.com/o/oauth2/v2/auth',
      accessTokenEndpoint: 'https://www.googleapis.com/oauth2/v4/token',
      responseType: 'token',
      scope: 'email profile openid',
      web: {
        redirectUrl: 'http://localhost',
        windowOptions: 'height=600,left=0,top=0'
      },
      android: {
        redirectUrl: 'com.your.package:/oauth2redirect',
        appId: '<YOUR_GOOGLE_CLIENT_ID>'
      }
    }
  }
};
export default config;
