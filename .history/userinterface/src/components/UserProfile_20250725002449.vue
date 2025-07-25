<template>
  <div class="user-profile-page">
    <header class="header">
      <img src="@/assets/Profile.jpeg" alt="Profile" class="profile-image" />
      <h1>User Profile</h1>
    </header>

    <div class="user-details">
      <label>Username:</label>
      <input v-model="username" type="text" />

      <div class="name-group">
        <div class="name-field">
          <label>First Name:</label>
          <input v-model="firstName" type="text" />
        </div>
        <div class="name-field">
          <label>Middle Name:</label>
          <input v-model="middleName" type="text" />
        </div>
        <div class="name-field">
          <label>Last Name:</label>
          <input v-model="lastName" type="text" />
        </div>
      </div>

      <label>Email Address:</label>
      <input v-model="email" type="email" />

      <label>Phone Number:</label>
      <input v-model="phone" type="tel" />

      <label>Birthday:</label>
      <input v-model="birthday" type="date" />

      <label>Password:</label>
      <input :value="hashedPassword" type="password" disabled />
      <button class="change-password">Change Password</button>
    </div>

    <div class="dependents-section">
      <h2>Dependents / Regular Contacts</h2>

      <div class="add-dependent-form">
        <h3>Add New Dependent / Contact</h3>
        <input v-model="newDependent.name" placeholder="Name" type="text" />
        <input v-model="newDependent.birthday" placeholder="Birthday (YYYY-MM-DD)" type="date" />
        <input v-model="newDependent.phone" placeholder="Phone Number (e.g., 07XXXXXXXX)" type="tel" />
        <input v-model="newDependent.relationship" placeholder="Relationship (e.g., Son, Wife)" type="text" />
        <button @click="addDependent" class="add-dependent-button">Add Dependent</button>
      </div>

      <div v-if="dependents.length" class="dependents-list">
        <h3>Your Dependents / Contacts:</h3>
        <ul>
          <li v-for="(dependent, index) in dependents" :key="index">
            <div class="dependent-info">
              <span><strong>{{ dependent.name }}</strong> ({{ dependent.relationship || 'N/A' }})</span>
              <span>📞 {{ dependent.phone }}</span>
              <span>🎂 {{ dependent.birthday || 'N/A' }}</span>
            </div>
            <div class="dependent-actions">
              <button
                @click="togglePinDependent(dependent)"
                :class="{ 'pinned': isPinned(dependent.phone) }"
                class="pin-button"
              >
                {{ isPinned(dependent.phone) ? 'Unpin' : 'Pin' }}
              </button>
              <button @click="removeDependent(index)" class="remove-button">Remove</button>
            </div>
          </li>
        </ul>
      </div>
      <p v-else class="no-dependents-message">No dependents added yet.</p>
    </div>

    <div class="actions">
      <button class="button" @click="logout">LOGOUT</button>
      <button class="button" @click="goToSignup">Create New Profile</button>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserProfile',
  data() {
    return {
      username: 'Pashie🦋',
      firstName: 'Patience',
      middleName: 'Njeri',
      lastName: 'Ndiritu',
      email: 'ndiritupatience002@gmail.com',
      phone: '0712345678',
      birthday: '2002-11-02',
      hashedPassword: '************', // Placeholder for actual hash
      dependents: [], // Array to store dependent objects
      newDependent: { // Object to store data for a new dependent being added
        name: '',
        birthday: '',
        phone: '',
        relationship: ''
      },
      pinnedContacts: [], // Array to store pinned contacts (phone numbers)
    };
  },
  mounted() {
    this.loadDependents();
    this.loadPinnedContacts();
  },
  methods: {
    loadDependents() {
      // Load dependents from localStorage if they exist
      const savedDependents = localStorage.getItem('userDependents');
      if (savedDependents) {
        this.dependents = JSON.parse(savedDependents);
      }
    },
    saveDependents() {
      // Save dependents to localStorage
      localStorage.setItem('userDependents', JSON.stringify(this.dependents));
    },
    loadPinnedContacts() {
      // Load pinned contacts from localStorage
      const savedPinned = localStorage.getItem('pinnedContacts');
      if (savedPinned) {
        this.pinnedContacts = JSON.parse(savedPinned);
      }
    },
    savePinnedContacts() {
      // Save pinned contacts to localStorage
      localStorage.setItem('pinnedContacts', JSON.stringify(this.pinnedContacts));
    },
    addDependent() {
      if (!this.newDependent.name || !this.newDependent.phone) {
        alert('Name and Phone Number are required to add a dependent/contact.');
        return;
      }
      // Basic phone number validation for Kenya (starts with 07, 10 digits total)
      const kenyanPhoneRegex = /^07\d{8}$/;
      if (!kenyanPhoneRegex.test(this.newDependent.phone)) {
        alert('Please enter a valid Kenyan phone number (e.g., 07XXXXXXXX).');
        return;
      }

      this.dependents.push({ ...this.newDependent }); // Add a copy of newDependent
      this.saveDependents(); // Save to local storage

      // Clear the form fields
      this.newDependent = {
        name: '',
        birthday: '',
        phone: '',
        relationship: ''
      };
      alert('Dependent/Contact added successfully!');
    },
    removeDependent(index) {
      const removedPhone = this.dependents[index].phone;
      this.dependents.splice(index, 1); // Remove from dependents array
      this.saveDependents(); // Update local storage

      // If the removed dependent was pinned, unpin them
      if (this.isPinned(removedPhone)) {
        this.pinnedContacts = this.pinnedContacts.filter(phone => phone !== removedPhone);
        this.savePinnedContacts();
      }
      alert('Dependent/Contact removed.');
    },
    isPinned(phoneNumber) {
      return this.pinnedContacts.includes(phoneNumber);
    },
    togglePinDependent(dependent) {
      const phoneNumber = dependent.phone;
      if (this.isPinned(phoneNumber)) {
        // Unpin
        this.pinnedContacts = this.pinnedContacts.filter(phone => phone !== phoneNumber);
        alert(`${dependent.name} unpinned.`);
      } else {
        // Pin
        this.pinnedContacts.push(phoneNumber);
        alert(`${dependent.name} pinned as a regular contact!`);
      }
      this.savePinnedContacts(); // Save updated pinned contacts
    },
    logout() {
      // In a real app, you would clear user session/token, not just UI data.
      // For this simulation, clearing UI data is sufficient.
      this.username = '';
      this.firstName = '';
      this.middleName = '';
      this.lastName = '';
      this.email = '';
      this.phone = '';
      this.birthday = '';
      this.hashedPassword = '';
      // Also clear dependents and pinned contacts on logout for this user
      localStorage.removeItem('userDependents');
      localStorage.removeItem('pinnedContacts');
      this.dependents = [];
      this.pinnedContacts = [];
      alert('Logged out!');
      // Optionally redirect to login page
      // this.$router.push('/login');
    },
    goToSignup() {
      this.$router.push('/signup');
    },
  },
};
</script>

<style scoped>
.user-profile-page {
  font-family: Arial, sans-serif;
  max-width: 700px;
  margin: 30px auto;
  padding: 20px;
  border: 1px solid #ccc;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  background-color: #fff;
  padding-bottom: 80px; /* Add padding for the global footer */
}

.header {
  display: flex;
  align-items: center;
  gap: 20px;
  margin-bottom: 30px;
  border-bottom: 1px solid #eee;
  padding-bottom: 20px;
}

.profile-image {
  width: 80px;
  height: 80px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #42b983;
}

h1 {
  color: #333;
  font-size: 2em;
}

.user-details {
  display: flex;
  flex-direction: column;
  gap: 15px;
  margin-bottom: 30px;
}

.user-details label {
  font-weight: bold;
  margin-top: 10px;
  color: #555;
  text-align: left;
}

input {
  padding: 10px;
  border: 1px solid #ddd;
  border-radius: 5px;
  width: calc(100% - 22px); /* Account for padding and border */
  font-size: 1em;
}

.name-group {
  display: flex;
  gap: 10px;
  flex-wrap: wrap; /* Allow wrapping on small screens */
}

.name-field {
  flex: 1;
  display: flex;
  flex-direction: column;
  min-width: 150px; /* Ensure fields don't get too small */
}

.change-password {
  margin-top: 10px;
  background-color: #6c757d;
  color: white;
  border: none;
  padding: 10px;
  border-radius: 5px;
  cursor: pointer;
  align-self: flex-start; /* Align button to the start */
  font-size: 0.9em;
  transition: background-color 0.3s;
}
.change-password:hover {
  background-color: #5a6268;
}

/* Dependents Section Styles */
.dependents-section {
  margin-top: 40px;
  border-top: 1px solid #eee;
  padding-top: 30px;
}

.dependents-section h2 {
  color: #333;
  margin-bottom: 25px;
  font-size: 1.8em;
}

.add-dependent-form {
  background-color: #f9f9f9;
  border: 1px solid #e0e0e0;
  border-radius: 8px;
  padding: 20px;
  margin-bottom: 30px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.add-dependent-form h3 {
  color: #007BFF;
  margin-top: 0;
  margin-bottom: 15px;
}

.add-dependent-form input {
  width: calc(100% - 20px);
}

.add-dependent-button {
  background-color: #42b983;
  color: white;
  padding: 10px 15px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 1em;
  margin-top: 10px;
  transition: background-color 0.3s;
}

.add-dependent-button:hover {
  background-color: #36a374;
}

.dependents-list ul {
  list-style: none;
  padding: 0;
}

.dependents-list li {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 15px;
  border: 1px solid #eee;
  border-radius: 8px;
  margin-bottom: 10px;
  background-color: #fcfcfc;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
  flex-wrap: wrap; /* Allow items to wrap */
  gap: 10px; /* Space between wrapped items */
}

.dependent-info {
  display: flex;
  flex-direction: column;
  align-items: flex-start;
  flex-grow: 1; /* Allow info section to take available space */
}

.dependent-info span {
  margin-bottom: 5px;
  color: #333;
}

.dependent-info span:last-child {
  margin-bottom: 0;
}

.dependent-actions {
  display: flex;
  gap: 10px;
  margin-left: 15px; /* Space from info */
}

.pin-button, .remove-button {
  padding: 8px 12px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 0.9em;
  transition: background-color 0.3s;
}

.pin-button {
  background-color: #007BFF;
  color: white;
}
.pin-button:hover {
  background-color: #0056b3;
}
.pin-button.pinned {
  background-color: #ffc107; /* Orange/Yellow when pinned */
  color: #333;
}
.pin-button.pinned:hover {
  background-color: #e0a800;
}

.remove-button {
  background-color: #dc3545;
  color: white;
}
.remove-button:hover {
  background-color: #c82333;
}

.no-dependents-message {
  color: #777;
  font-style: italic;
  margin-top: 20px;
}

.actions {
  display: flex;
  justify-content: space-between;
  margin-top: 30px;
  border-top: 1px solid #eee;
  padding-top: 20px;
}

.button {
  padding: 10px 20px;
  font-size: 16px;
  background-color: #42b983; /* Green by default */
  color: white;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

/* Specific button styles as per your previous request */
.button.red {
  background-color: red;
}

.button.orange {
  background-color: rgb(0, 255, 85);
}

.button:hover {
  background-color: orange;
}

.button:active {
  background-color: red;
}
</style>