//
//  DebugLog.swift
//  Vault
//

import Foundation

/// Drop-in replacement for `print()`/`debugPrint()` that only writes output in DEBUG builds,
/// so debug logging never reaches the App Store build's console.
func debugLog(_ items: Any..., separator: String = " ", terminator: String = "\n") {
    #if DEBUG
    let message = items.map { "\($0)" }.joined(separator: separator)
    Swift.print(message, terminator: terminator)
    #endif
}
