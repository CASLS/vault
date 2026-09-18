//
//  SignUpViewController.swift
//  Vault
//
//  Created by Carl Burnstein on 3/18/19.
//  Copyright © 2019 CASLS.
//

import UIKit

class SignUpViewController: UIViewController, UITextFieldDelegate {

    @IBOutlet var emailInputField: UITextField!
    @IBOutlet var passwordInputField: UITextField!
    @IBOutlet var passwordRepeatInputField: UITextField!
    
    
    override func viewDidLoad() {
        super.viewDidLoad()

        // Do any additional setup after loading the view.
    }
    

    /*
    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destination.
        // Pass the selected object to the new view controller.
    }
    */

    func textFieldShouldReturn(_ textField: UITextField) -> Bool {
        self.view.endEditing(true)
        return false
    }
    
    @IBAction func dismissButtonTouched(_ sender: Any) {
        self.dismiss(animated: true, completion: nil)
    }
    
    @IBAction func signUpButtonTouched(_ sender: Any) {
        let email = emailInputField.text
        let password1 = passwordInputField.text
        let password2 = passwordRepeatInputField.text
        
        emailInputField.resignFirstResponder()
        passwordInputField.resignFirstResponder()
        passwordRepeatInputField.resignFirstResponder()
        
        if(password1 != password2){
            self.showErrorMessage(title: "Error", body: "Passwords must match.", presentationStyle: .top, duration: 3, buttonTapHandler: nil, buttonTitle: nil, image: nil)
        }else{
            ApiController.signUp(email: email!, password1: password1!, password2: password2!, completion: { (success, user, errorMsg)  in
                if(success == true){
                    //Perform the unwind segue.
                    self.performSegue(withIdentifier: "unwindSignUpSegue", sender: self)
                }else{
                    debugLog("something went wrong")
                    self.showErrorMessage(title: "Error", body: errorMsg!, presentationStyle: .top, duration: 3, buttonTapHandler: nil, buttonTitle: nil, image: nil)
                }
            })
        }
    }
}
