<template>
	<div>
		<Modal @close="toggleModal()">
			<div class="modal-content">
				<button class="edit-button" @click="editClient()">
					{{ editText }}
				</button>
				<div class="client-info">
					<div class="col w-60">
						<div class="row">
							<Avatar :username="name" :size="100" />
							<div v-if="editMode" class="col ml-22">
								<input v-model="name" />
								<div class="row">
									<input v-model="city" />
									<input v-model="timezone" />
								</div>
								<input v-model="age" />
							</div>
							<div v-else class="col ml-22">
								<h1>
									{{ name }}
								</h1>
								<p>
									{{ city }},
									<span>{{ timezone }}</span>
								</p>
								<p>{{ textAge }}</p>
							</div>
						</div>

						<h3>About</h3>
						<textarea v-if="editMode" v-model="description" />
						<p v-else>
							{{ description }}
						</p>
					</div>
					<div class="col ml-22">
						<h3>Other Contacts</h3>
						<textarea v-if="editMode" v-model="contacts" />
						<span v-else> {{ contacts }}</span>
						<h3>Attachments</h3>
					</div>
				</div>
				<div class="line"></div>
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
import { SessionsUtil } from "../utils.js";
import SessionCard from "./SessionCard";

export default {
	components: {
		Modal,
		Avatar,
		SessionCard,
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
			name: this.client.name,
			description: this.client.description,
			city: this.client.city,
			timezone: this.client.timezone,
			age: this.client.age,
			contacts: this.client.contacts,
		};
	},
	computed: {
		textAge() {
			return this.age + " Years Old";
		},
		editText() {
			return this.editMode ? "Save Changes" : "Edit";
		},
	},
	async mounted() {
		this.sessions = await SessionsUtil.fetchSessions();
	},
	methods: {
		toggleModal() {
			this.$emit("toggle-modal", false);
		},
		editClient() {
			this.editMode = !this.editMode;
		},
	},
};
</script>
<style>
.modal-container {
	width: 80vw !important;
}
</style>
<style scoped>
input {
	width: 100%;
}

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
	padding: 0 !important;
}

p span {
	color: #346188;
}

.edit-button {
	margin-left: auto;
	display: flex;
	border: none;
	box-shadow: none;
}
</style>