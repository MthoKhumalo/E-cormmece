function validatePhoneNumber() {

    var phoneNumber = document.getElementById("phone").value.trim();
    
    // Check if the phone number is composed entirely of digits
    if (!/^\d{10}$/.test(phoneNumber)) {
        
        alert("Phone number must be exactly 10 digits.");
        return false;
    }
    return true;
}
