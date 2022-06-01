<template>
	<div class="session">
		<div class="session-header">
			<p class="blue">
				<span>{{ session.date }} - {{ session.title }}</span>
			</p>
			<!-- <div v-if="session.paid" class="payment">
				<svg
					width="24"
					height="24"
					viewBox="0 0 23 23"
					fill="none"
					xmlns="http://www.w3.org/2000/svg"
				>
					<circle cx="11.5" cy="11.5" r="11.5" fill="#6295E2" />
					<path
						d="M16.2453 6.13281L10.0894 14.2489L6.44987 10.612L4.77051 12.2914L10.3675 17.8884L18.2054 7.81218L16.2453 6.13281Z"
						fill="white"
					/>
				</svg>

				<span> PAID {{ session.value }}</span>
			</div>
			<div v-else class="payment">
				<svg
					width="23"
					height="23"
					viewBox="0 0 23 23"
					fill="none"
					xmlns="http://www.w3.org/2000/svg"
				>
					<circle
						cx="11.5"
						cy="11.5"
						r="10.5"
						stroke="#6295E2"
						stroke-width="2"
					/>
				</svg>
				<span class="payment">UNPAID</span>
			</div> -->
		</div>
		<div class="description">
			{{ safeDescription }}

			<a :href="mainLink" class="link blue">{{ mainLink }}</a>
		</div>
	</div>
</template>

<script>
import sanitizeHtml from "sanitize-html";

export default {
	props: {
		session: {
			type: Object,
			default() {
				return {};
			},
		},
	},
	computed: {
		mainLink() {
			// Find last link in description
			const links = [
				...(this.linkify(this.session.description || "") || []),
			].filter(Boolean);

			return links.pop();
		},
		safeDescription() {
			return this.session.description
				? sanitizeHtml(this.session.description).replace(
						this.mainLink,
						""
				  )
				: "";
		},
	},
	methods: {
		linkify(text) {
			const urlRegex =
				/(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/gi; // eslint-disable-line
			return text.match(urlRegex);
		},
	},
};
</script>

<style scoped>
.blue {
	color: #346188;
}

.session-header {
	margin-top: 20px;
	display: flex;
	flex-direction: row;
}

svg {
	padding: 0 5px 0 20px;
}

.session {
	border-bottom: 1px solid lightgray;
}

.description {
	margin-bottom: 0.75rem;
}

.payment {
	display: flex;
	flex-direction: row;
}

.payment span {
	font-weight: 500;
}

.link {
	text-decoration: underline;
}
</style>
