//
//  SpeechRecognitionViewController.swift
//  Vault
//
//  Created by Carl Burnstein on 3/27/19.
//  Copyright © 2019 CASLS.
//

import UIKit
import Speech
import AVFoundation

protocol SpeechRecognitionViewDelegate{
    func recognitionTaskCompleted(with result:String, sender: Any)
}

class SpeechRecognitionViewController: UIViewController {
    @IBOutlet weak var speechButton: UIButton!
    @IBOutlet weak var resultLabel: UILabel!
    @IBOutlet weak var waveFormView: SFWaveformView!
    @IBOutlet weak var correctResponseImageView: UIImageView!
    
    var speechRecognizer : SFSpeechRecognizer!
    var request : SFSpeechAudioBufferRecognitionRequest!
    var recognitionTask: SFSpeechRecognitionTask?
    var mostRecentlyProcessedSegmentDuration: TimeInterval = 0

    var inputNode : AVAudioInputNode!
    var displayLink : CADisplayLink!
    var isRecording : Bool = false
    
    let startImage = UIImage(named: "record")
    let recordingImage = UIImage(named: "recording")
    
    var delegate:SpeechRecognitionViewDelegate? = nil
    
    var speechRecorder : AVAudioRecorder!
    let audioEngine = AVAudioEngine()
    let audioSession = AVAudioSession.sharedInstance()
    
    var localeTaskMeta : TaskMeta!
    
    override func viewDidLoad() {
        super.viewDidLoad()
        
        speechButton.contentMode = .scaleAspectFit
    }
    
    override func viewDidAppear(_ animated: Bool) {
        super.viewDidAppear(animated)
        
        // Make the authorization request
       SFSpeechRecognizer.requestAuthorization { authStatus in
           
           // The authorization status results in changes to the
           // app’s interface, so process the results on the app’s
           // main queue.
           OperationQueue.main.addOperation {
               switch authStatus {
               case .authorized:
                   debugLog("Is authorized!")
                   
               case .denied:
                   debugLog("Speech recognition authorization denied")
                   self.showErrorMessage(title: "Error", body: "Speech recognition authorization denied.", presentationStyle: .top, duration: 4.0, buttonTapHandler: nil, buttonTitle: nil, image: nil)
                   
               case .restricted:
                    debugLog("Not available on this device")
                    self.showErrorMessage(title: "Error", body: "Not available on this device.", presentationStyle: .top, duration: 4.0, buttonTapHandler: nil, buttonTitle: nil, image: nil)
                   
               case .notDetermined:
                   debugLog("Not determined")
               }
           }
       }
        
        var localeStr = "en-US" //Defaults to English for speech recognition
        if let localeMeta = localeTaskMeta{
            localeStr = localeMeta.value //Use the locale from the TaskMeta object
        }
        //Set up the speech recognizer with locale
        speechRecognizer = SFSpeechRecognizer(locale: Locale(identifier: localeStr))
        
        //Start the display link that calls the updater for the waveform periodically
        displayLink = CADisplayLink(target: self, selector: #selector(self.updateMeters))
        displayLink.add(to: RunLoop.current, forMode: RunLoop.Mode.common)
    }
    
    override func viewWillDisappear(_ animated: Bool) {
        super.viewWillDisappear(animated)
        
        //If we're in the middle of recording stop recording before view is dismissed
        if(isRecording){
            finishRecording(success: false) //Stop recording
        }
        
        if (displayLink != nil && displayLink.isPaused == false){
            //Remove the displaylin kfrom the run loop
            displayLink.remove(from: RunLoop.current, forMode: RunLoop.Mode.common)
        }
        displayLink = nil //dealloc
    }
    
    //Method that updates the waveFormView when called
    @objc func updateMeters() {
        if let recorder = speechRecorder{
            if(isRecording){
                //Update recorder meter values
                recorder.updateMeters()
                //get the average power level
                let power = recorder.averagePower(forChannel: 0)
                //Normalize the value
                let normalizedValue = pow(10, power / 20)
                waveFormView.updateWithLevel(CGFloat(normalizedValue))
            }
        }
    }
    
    @IBAction func speechButtonTouched(_ sender: Any) {
        //If we're currently recording
        if(isRecording){
            finishRecording(success: true)
        }else{
            if(speechRecognizer.isAvailable){
                do {
                    //Start recording
                    try self.startRecording()
                } catch let error {
                    debugLog("There was a problem starting recording: \(error.localizedDescription)")
                    self.showErrorMessage(title: "Error", body: "There was a problem starting recording: \(error.localizedDescription)", presentationStyle: .top, duration: 4.0, buttonTapHandler: nil, buttonTitle: nil, image: nil)
                }
            }else{
                self.showErrorMessage(title: "Error", body: "Speech recognition is not available at the moment.", presentationStyle: .top, duration: 4.0, buttonTapHandler: nil, buttonTitle: nil, image: nil)
            }
        }
    }
    
    /*
    // MARK: - Navigation

    // In a storyboard-based application, you will often want to do a little preparation before navigation
    override func prepare(for segue: UIStoryboardSegue, sender: Any?) {
        // Get the new view controller using segue.destination.
        // Pass the selected object to the new view controller.
    }
    */

}

extension SpeechRecognitionViewController {
    fileprivate func startRecording() throws {
        speechButton.setImage(recordingImage, for: .normal) //Set the speech button image
        
        // Cancel the previous task if it's running.
        if let recognitionTask = recognitionTask {
            recognitionTask.cancel()
            self.recognitionTask = nil
        }
        
        //Create a new streaming audio buffer speech recognition request
        request = SFSpeechAudioBufferRecognitionRequest()

        // Configure request so that results are returned before audio recording is finished
        request.shouldReportPartialResults = true

        // A recognition task represents a speech recognition session.
        // We keep a reference to the task so that it can be cancelled.
        recognitionTask = speechRecognizer.recognitionTask(with: request) { result, error in
            var isFinal = false
            
            if let result = result {
                //Set the result label text with the best transcription
                self.resultLabel.text = result.bestTranscription.formattedString
                isFinal = result.isFinal
                if(isFinal){
                    //Set the final result string when task is finished
                    let resultString = result.bestTranscription.formattedString
                    debugLog(resultString)
                    self.resultLabel.text = resultString
                    if let delegate = self.delegate{
                        delegate.recognitionTaskCompleted(with: resultString, sender: self)
                    }
                }
            }
            
            if error != nil || isFinal{
                
                if(error != nil){
                    debugLog(error?.localizedDescription as Any)
                    self.showErrorMessage(title: "Uh oh!", body: "Something went wrong.  Please try recording again. Error: \(error!.localizedDescription)", presentationStyle: .center, duration: 5, buttonTapHandler: nil, buttonTitle: nil, image: nil)
                }
            }
        }
        
        //Get the audio URL for where the audio recorder records to.
        let audioURL = self.getRecognitionURL()

        // create recorder settings
        let settings = [
            AVFormatIDKey: Int(kAudioFormatMPEG4AAC),
            AVSampleRateKey: 12000,
            AVNumberOfChannelsKey: 1,
            AVEncoderAudioQualityKey: AVAudioQuality.high.rawValue
        ]
        
        //Show the waveform view
        UIView.animate(withDuration: 0.5, animations: {
            self.waveFormView?.alpha = 1
        })
        
        do {
            // Configure the audio session for playback.
            try audioSession.setCategory(.playback, mode: .default, options: .duckOthers)
            try audioSession.setActive(true, options: .notifyOthersOnDeactivation)

            //Play a beep and vibrate
            AudioServicesPlaySystemSound(SystemSoundID(kSystemSoundID_Vibrate))
            AudioServicesPlaySystemSoundWithCompletion(1113, {
                do {
                    //Configure the audio session for recording, DEFAULT mode is important
                    try self.audioSession.setCategory(.record, mode: .default, options: .mixWithOthers)
                    try self.audioSession.setActive(true, options: .notifyOthersOnDeactivation)
                } catch {
                    self.finishRecording(success: false)
                }
                
                //Get the mic input node from the audio engine
                self.inputNode = self.audioEngine.inputNode
                // Configure the microphone input.
                let recordingFormat = self.inputNode.outputFormat(forBus: 0)
                //Tap the input node and send the buffer into the speech recognition request
                self.inputNode.installTap(onBus: 0, bufferSize: 1024, format: recordingFormat) { (buffer: AVAudioPCMBuffer, when: AVAudioTime) in
                    self.request.append(buffer)
                }
                
                //Prepare and start the audio engine
                self.audioEngine.prepare()
                do {
                    try self.audioEngine.start()
                } catch {
                    self.finishRecording(success: false)
                }
                
                //Create the audio recorder object for listenting to mic levels
                self.speechRecorder = try? AVAudioRecorder(url: audioURL, settings: settings)
                self.speechRecorder.delegate = self
                self.speechRecorder.isMeteringEnabled = true //make sure to enable metering
                self.speechRecorder.prepareToRecord()
                self.speechRecorder.record() //Start recording/listening
                self.isRecording = true //Set is recording to true
            })
        } catch {
            finishRecording(success: false)
        }

    }
    
    func finishRecording(success: Bool) {
        
        speechButton.setImage(startImage, for: .normal) //Set the speech button image
        
        //Stop the audio recorder
        speechRecorder.stop()
        speechRecorder = nil
        
        isRecording = false //Set is recording to false
        
        //Set the audio session to playback
        try? audioSession.setCategory(.playback, mode: .default, options: .duckOthers)
        try? audioSession.setActive(true, options: .notifyOthersOnDeactivation)
        //Play the beep and vibrate
        AudioServicesPlaySystemSound(SystemSoundID(kSystemSoundID_Vibrate))
        AudioServicesPlaySystemSound(1114)
        
        //Hide the waveform view
        UIView.animate(withDuration: 0.5, animations: {
            self.waveFormView?.alpha = 0
        })
        
        //Remove the tap on the mic input node
        self.inputNode.removeTap(onBus: 0)
        if success {
            request.endAudio() //End the speech recognition request
            recognitionTask?.finish() //Finish the speech recognition task
            request = nil //dealloc
            recognitionTask = nil //dealloc
            inputNode = nil //dealloc
            audioEngine.stop() //Stop the audio engine
            
        } else {
            self.showErrorMessage(title: "Record failed", body: "There was a problem recording your speech; please try again.", presentationStyle: .center, duration: 5, buttonTapHandler: nil, buttonTitle: nil, image: nil)
        }
    }
    
    func getDocumentsDirectory() -> URL {
        let paths = FileManager.default.urls(for: .documentDirectory, in: .userDomainMask)
        let documentsDirectory = paths[0]
        return documentsDirectory
    }
    
    func getRecognitionURL() -> URL {
        return getDocumentsDirectory().appendingPathComponent("recognition.m4a")
    }
}


extension SpeechRecognitionViewController: AVAudioRecorderDelegate{
    func audioRecorderDidFinishRecording(_ recorder: AVAudioRecorder, successfully flag: Bool) {
        if !flag {
            finishRecording(success: false)
        }
    }
}
