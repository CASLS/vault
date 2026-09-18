# VAuLT iOS App (vault_ios)

The mobile companion app for the VAuLT platform. Written in Swift, built with
CocoaPods, targeting iOS 13+. It talks to the `frontend` JSON API of the
[VAuLT backend](../vault_web/README.md) at `/api/*`.

## Requirements

- Xcode with CocoaPods installed
- **A physical iOS device to build and run on.** The `GVRSDK` pod (Google's
  discontinued VR/Cardboard SDK, used by the 360° photo/video task types)
  ships as a device-only static library with no Simulator-compatible slice. Device builds compile and link cleanly.

## Setup

```bash
cd vault_ios/Vault
pod install
open Vault.xcworkspace
```

## Placeholders you must fill in

1. **Backend URL**: [`Vault/Vault/ApiController.swift`](Vault/Vault/ApiController.swift),
   `Router.serverURLString`. Point this at your backend's `frontend` host
   (Docker default: `http://localhost`, or your own domain in production).

2. **Google Sign-In client IDs** *(optional, only for the Google button; other login methods don't need this)*:
   [`Vault/Vault/AppDelegate.swift`](Vault/Vault/AppDelegate.swift):
   - `GIDSignIn.sharedInstance().clientID`: your **iOS** OAuth client.
   - `GIDSignIn.sharedInstance().serverClientID`: your **Web** OAuth client;
     must match the `client_id` in the backend's `google_credentials.json` /
     `client_secret.json` (see [vault_web/README.md](../vault_web/README.md#google-oauth-setup-optional)).

3. **URL scheme** *(only if you did #2)*: [`Vault/Vault/Info.plist`](Vault/Vault/Info.plist),
   `CFBundleURLSchemes`. Reversed form of the iOS client ID from step 2,
   e.g. `1234-abc.apps.googleusercontent.com` becomes
   `com.googleusercontent.apps.1234-abc`.

4. **Development Team** *(required, device-only builds need signing, even
   for local debug runs)*: in Xcode, select the `Vault` target → Signing &
   Capabilities → set your own Apple Developer Team (a free personal team
   from your Apple ID works for local testing).

5. **Bundle identifier** *(optional)*: currently `com.example.vault`
   (Xcode project settings).

The login screen also has an **Apple Sign-In** button
(`ASAuthorizationController` in `LoginViewController.swift`), which needs its
own Apple Developer Services ID/key.

## Notes

- See [`Podfile`](Vault/Podfile) for the full dependency list (Alamofire,
  GoogleSignIn 5.0.2, SwiftyJSON, and others).
