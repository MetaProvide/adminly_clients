<template>
	<div>
		<Modal @close="toggleModal()">
			<div class="modal-content">
				<div class="client-info">
					<div class="col w-60">
						<div class="row">
							<Avatar :username="client.name" :size="100" />
							<div class="col ml-22">
								<h1>
									{{ client.name }}
								</h1>
								<p>
									{{ client.city }},
									<span>{{ client.timezone }}</span>
								</p>
								<p>{{ age }}</p>
							</div>
						</div>

						<h3>About</h3>
						<p>
							{{ client.description }}
						</p>
					</div>
					<div class="col ml-22">
						<h3>Other Contacts</h3>
						{{ client.contacts }}
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
		return { sessions: [] };
	},
	computed: {
		age() {
			return this.client.age + " Years Old";
		},
	},
	async mounted() {
		this.sessions = await SessionsUtil.fetchSessions();
	},
	methods: {
		toggleModal() {
			this.$emit("toggle-modal", false);
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
</style>
