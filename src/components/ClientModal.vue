<template>
	<div>
		<Modal @close="toggleModal()">
			<div class="modal-content">
				<div class="modal-header">
					<button v-if="editMode" @click="toggleEdit()">
						Cancel
					</button>
					<button
						v-else
						class="svg edit-button"
						@click="editClient()"
					></button>
					<button
						class="svg close-button"
						@click="toggleModal()"
					></button>
				</div>
				<div class="client-info">
					<div class="col w-60">
						<div class="row">
							<Avatar
								:username="mutableClient.name"
								:size="100"
								class="avatar"
							/>
							<div v-if="editMode" class="col ml-22">
								<input
									v-model="mutableClient.name"
									placeholder="Name"
									class="name-input"
									required
								/><input
									v-model="mutableClient.age"
									class="age-input"
									type="number"
									placeholder="Age"
								/>
								<input
									v-model="mutableClient.email"
									placeholder="Email"
									type="email"
									class="email"
									required
								/>
								<input
									v-model="mutableClient.phoneNumber"
									placeholder="Phone Number"
									type="tel"
									class="phone"
									required
								/>
								<input
									v-model="mutableClient.city"
									placeholder="City"
									class="city"
								/>
								<TimezonePicker
									v-model="mutableClient.timezone"
								/>
							</div>
							<div v-else class="col ml-22">
								<h1 @dblclick="editClient()">
									{{ mutableClient.name }}
								</h1>
								<div @dblclick="editClient()">
									<div class="row">
										<span class="icon email-icon"></span>
										{{ mutableClient.email }}
									</div>
									<div
										v-if="mutableClient.phoneNumber"
										class="row"
									>
										<span class="icon phone-icon"></span>
										<a
											:href="
												'tel:' +
												mutableClient.phoneNumber
											"
											>{{ mutableClient.phoneNumber }}</a
										>
									</div>
								</div>
								<div class="row" @dblclick="editClient()">
									<span class="icon location-icon"></span>
									{{ mutableClient.city
									}}{{ commaCityTimezone }}
									<span>{{ displayTimezone }}</span>
								</div>
								<p @dblclick="editClient()">{{ textAge }}</p>
							</div>
						</div>

						<h3>About</h3>
						<textarea
							v-if="editMode"
							v-model="mutableClient.description"
							placeholder="Description"
							class="client-description"
						/>
						<p
							v-else-if="mutableClient.description"
							@dblclick="editClient()"
						>
							{{ mutableClient.description }}
						</p>
					</div>
					<div class="col ml-22 other-contacts">
						<h3 v-if="mutableClient.contacts || editMode">
							Other Contacts
						</h3>
						<textarea
							v-if="editMode"
							v-model="mutableClient.contacts"
							placeholder="John Doe +460406280400, Jane Doe +441134960000"
							title="John Doe +460406280400, Jane Doe +441134960000"
							class="contacts-list"
						/>
						<div v-else @dblclick="editClient()">
							<ul>
								<li
									v-for="(contact, index) in contactsList"
									:key="index"
								>
									<a :href="'tel:' + linkfyPhone(contact)">{{
										linkfyPhone(contact)
									}}</a>
									{{
										contact.replace(
											linkfyPhone(contact),
											""
										)
									}}
								</li>
							</ul>
						</div>
						<!-- <h3>Attachments</h3> -->
					</div>
				</div>
				<div v-if="sessions.length" class="line"></div>
				<div class="sessions">
					<SessionCard
						v-for="session in sessions"
						:key="session.id"
						:session="session"
					/>
				</div>
				<div v-if="editMode" class="modal-footer">
					<button @click="toggleDeleteModal()">Delete Client</button>
					<button class="update" @click="editClient()">Update</button>
				</div>
			</div>
		</Modal>
		<ClientDeletion
			v-if="deleteModal"
			:client="client"
			@toggle-modal="toggleDeleteModal"
			@update-clients="updateClients"
		/>
	</div>
</template>

<script>
import { Modal } from "@nextcloud/vue";
import Avatar from "vue-avatar";
import { SessionsUtil, ClientsUtil, TimezoneUtil } from "../utils.js";
import SessionCard from "./SessionCard";
import ClientDeletion from "./ClientDeletion";
import TimezonePicker from "@nextcloud/vue/dist/Components/TimezonePicker";

export default {
	components: {
		Modal,
		Avatar,
		SessionCard,
		TimezonePicker,
		ClientDeletion,
	},

	props: {
		client: {
			type: Object,
			default() {
				return {};
			},
		},
	},
	data() {
		return {
			sessions: [],
			editMode: false,
			deleteModal: false,
			mutableClient: {
				id: this.client.id,
				name: this.client.name,
				description: this.client.description,
				city: this.client.city,
				country: this.client.country,
				timezone: this.client.timezone ? this.client.timezone : "UTC",
				age: this.client.age,
				contacts: this.client.contacts,
				email: this.client.email,
				phoneNumber: this.client.phoneNumber,
			},
		};
	},
	computed: {
		textAge() {
			return this.mutableClient.age
				? this.mutableClient.age + " Years Old"
				: "";
		},
		commaCityTimezone() {
			return this.mutableClient.city && this.mutableClient.timezone
				? ","
				: "";
		},
		displayTimezone() {
			return TimezoneUtil.timezoneWithUTC(
				this.mutableClient.timezone.slice(0)
			);
		},
		contactsList() {
			return this.mutableClient.contacts
				? this.mutableClient.contacts.split(",")
				: "";
		},
	},
	async mounted() {
		this.sessions = await SessionsUtil.fetchSessions(this.client.id);
	},
	methods: {
		toggleModal() {
			this.$emit("toggle-modal", false);
		},
		toggleDeleteModal() {
			this.deleteModal = !this.deleteModal;
		},
		toggleEdit() {
			this.editMode = !this.editMode;
		},
		async editClient() {
			this.editMode = !this.editMode;
			if (!this.editMode) {
				const res = await ClientsUtil.updateClient(this.mutableClient);
				if (res.status === 200) {
					this.$emit("update-clients", true);
					this.sessions = await SessionsUtil.fetchSessions(
						this.client.id
					);
				}
			}
		},
		getContactName(contact) {
			return contact.split(" ").slice(1).join(" ");
		},
		linkfyPhone(text) {
			const phoneRegex = /\+?[1-9][0-9]{7,14}/g; // eslint-disable-line
			return text.match(phoneRegex) ? text.match(phoneRegex)[0] : "";
		},
		updateClients() {
			this.$emit("update-clients", true);
			this.toggleModal();
		},
	},
};
</script>
<style>
.client-info .multiselect .multiselect__tags input.multiselect__input {
	font-size: 0.8rem !important;
	height: 34px !important;
	margin-top: 3px;
	border-radius: var(--border-radius) var(--border-radius) 0 0 !important;
}

.multiselect__input:focus {
	border: 1px solid var(--color-border-dark) !important;
}

.multiselect__single {
	box-sizing: border-box;
	font-size: 0.8rem !important;
	height: 34px;
	margin: 3px;
}

.multiselect__content-wrapper {
	top: 38px;
	bottom: auto !important;
}

.multiselect__tags {
	max-width: 200px;
}
</style>
<style scoped>
button {
	border: none;
	color: var(--adminly-blue);
	background-color: white;
}

.sessions {
	padding: 0 2.25rem 2.25rem 2.25rem;
}

.client-main {
	display: flex;
}

.modal-content h1 {
	font-weight: 500;
	font-size: 33px;
	line-height: 2.5rem;
	color: var(--adminly-dark-blue);
}

.modal-content h3 {
	font-weight: 600;
	font-size: 18px;
	line-height: 2.2rem;
	color: var(--adminly-dark-blue);
}

.modal-content {
	min-width: 515px;
}

.col {
	display: flex;
	flex-direction: column;
}

.row {
	display: flex;
	flex-direction: row;
}

.ml-22 {
	margin-left: 22px;
}

.w-60 {
	width: 60%;
}

.other-contacts {
	width: 33%;
}

.client-info {
	padding: 2.25rem 2.25rem 0.7rem 2.25rem;
	display: flex;
	flex-direction: row;
}

.line {
	border-bottom: 1px solid lightgray;
	padding: 0;
}

p a,
p span,
li a {
	color: var(--adminly-dark-blue);
}

li a {
	color: var(--adminly-link-blue);
}

.modal-header {
	margin-left: auto;
	display: flex;
	justify-content: end;
}

.modal-header button {
	padding: 1rem;
	border: none;
	box-shadow: none;
}

.modal-header span {
	color: var(--adminly-dark-blue);
}

.age-input {
	width: 55px;
}

input {
	width: 100%;
}

.client-description {
	width: 100%;
	height: 75px;
	resize: none;
}

.contacts-list {
	width: 100%;
	height: 100px;
}

.avatar {
	min-width: 100px;
}

.update {
	color: white;
	background: var(--adminly-blue);
	border-radius: 6px;
}

.modal-footer {
	justify-content: flex-end;
	padding: 0rem 2rem 1.5rem;
}

.icon::before {
	content: "";
	background-repeat: no-repeat;
	background-position: left;
	padding: 0.25rem 0.6rem;
	background-size: 1rem;
}

.email-icon::before {
	background-image: url("../../img/email.svg");
}

.phone-icon::before {
	background-image: url("../../img/phone.svg");
}

.location-icon::before {
	background-image: url("../../img/location.svg");
}

button {
	font-family: "Roc Grotesk", var(--font-face);
}

.icon {
	padding-right: 1rem;
}
</style>
