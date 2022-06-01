<template>
	<div>
		<Modal @close="toggleModal()">
			<div class="modal-content">
				<button class="edit-button" @click="editClient()">
					<span v-if="editMode">Save Changes</span>
					<svg
						v-else
						xmlns="http://www.w3.org/2000/svg"
						width="18"
						height="19"
						viewBox="0 0 18 19"
						fill="none"
					>
						<path
							d="M15.5613 0.900391L17.9153 3.25444L9.67656 11.4932L7.32251 9.13915L15.5613 0.900391Z"
							fill="#8E8E8E"
						/>
						<path
							d="M6.94895 9.68283L6.0454 11.7911L5.14186 13.8994L9.35842 12.0923L6.94895 9.68283Z"
							fill="#8E8E8E"
						/>
						<path
							d="M16.1049 7.15289V16.4293H2.61192V2.93633H11.8884V1.2497H2.61192C1.68042 1.2497 0.925293 2.00483 0.925293 2.93633V16.4293C0.925293 17.3608 1.68042 18.116 2.61192 18.116H16.1049C17.0364 18.116 17.7915 17.3608 17.7915 16.4293V7.15289H16.1049Z"
							fill="#8E8E8E"
						/>
					</svg>
				</button>
				<div class="client-info">
					<div class="col w-60">
						<div class="row">
							<Avatar
								:username="mutableClient.name"
								:size="100"
							/>
							<div v-if="editMode" class="col ml-22">
								<input
									v-model="mutableClient.name"
									placeholder="Name"
									required
								/>
								<div class="row">
									<input
										v-model="mutableClient.city"
										placeholder="City"
									/>
									<TimezonePicker
										v-model="mutableClient.timezone"
									/>
								</div>
								<input
									v-model="mutableClient.age"
									class="age-input"
									type="number"
									placeholder="Age"
								/>
							</div>
							<div v-else class="col ml-22">
								<h1 @dblclick="editClient()">
									{{ mutableClient.name }}
								</h1>
								<p @dblclick="editClient()">
									{{ mutableClient.city
									}}{{ commaCityTimezone }}
									<span>{{ displayTimezone }}</span>
								</p>
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
						<p v-else @dblclick="editClient()">
							{{ mutableClient.description }}
						</p>
					</div>
					<div class="col ml-22">
						<h3>Other Contacts</h3>
						<textarea
							v-if="editMode"
							v-model="mutableClient.contacts"
							placeholder="Contacts List"
							class="contacts-list"
						/>
						<span v-else @dblclick="editClient()">
							{{ mutableClient.contacts }}</span
						>
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
			</div>
		</Modal>
	</div>
</template>

<script>
import { Modal } from "@nextcloud/vue";
import Avatar from "vue-avatar";
import { SessionsUtil, ClientsUtil } from "../utils.js";
import SessionCard from "./SessionCard";
import TimezonePicker from "@nextcloud/vue/dist/Components/TimezonePicker";

export default {
	components: {
		Modal,
		Avatar,
		SessionCard,
		TimezonePicker,
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
			mutableClient: {
				id: this.client.id,
				name: this.client.name,
				description: this.client.description,
				city: this.client.city,
				country: this.client.country,
				timezone: this.client.timezone ? this.client.timezone : "UTC",
				age: this.client.age,
				contacts: this.client.contacts,
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
			return this.mutableClient.timezone.slice(0).replace("_", " ");
		},
	},
	async mounted() {
		this.sessions = await SessionsUtil.fetchSessions(this.client.id);
	},
	methods: {
		toggleModal() {
			this.$emit("toggle-modal", false);
		},
		editClient() {
			this.editMode = !this.editMode;
			if (!this.editMode) {
				ClientsUtil.updateClient(this.mutableClient);
				this.$emit("update-clients", true);
			}
		},
	},
};
</script>
<style>
.modal-container {
	width: 80vw;
}

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
	box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.15);
	border-radius: 8px;
	background-color: white;
}

.sessions {
	padding: 0 35px 35px 35px;
}

.client-main {
	display: flex;
}

.modal-content h1 {
	font-weight: 500;
	font-size: 33px;
	line-height: 41px;
	color: #346188;
}

.modal-content h3 {
	font-weight: 600;
	font-size: 18px;
	line-height: 22px;
	color: #346188;
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

.client-info {
	padding: 35px 35px 10px 35px;
	display: flex;
	flex-direction: row;
}

.line {
	border-bottom: 1px solid lightgray;
	padding: 0;
}

p span {
	color: #346188;
}

.edit-button {
	margin-left: auto;
	display: flex;
	border: none;
	box-shadow: none;
	padding: 15px;
}

.age-input {
	width: 55px;
}

.client-description {
	width: 100%;
	height: 75px;
	resize: none;
}

.contacts-list {
	width: 100%;
	height: 100px;
	resize: none;
}
</style>
