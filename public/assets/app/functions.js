/* Scroll chat messages to bottom
–––––––––––––––––––––––––––––––––––––––––––––––––– */

function scrollToBottom() {
	var objChat = document.getElementById('messages');

	if ( objChat !== null ) {
		objChat.scrollTop = objChat.scrollHeight;
	}
}

document.addEventListener("DOMContentLoaded", function(event) { 
	scrollToBottom();
});

window.onresize = function(event) {
	scrollToBottom();
};

/* Vue js application logic
–––––––––––––––––––––––––––––––––––––––––––––––––– */

Vue.component('message-row', {
	template: `
		<tr>
			<td>
				<table>
					<tr class="content">
						<td>
							<div id="messages" class="list">
								<div v-if="messages.length == 0 && messageHistory.length == 0" class="row item text-center">
									Type your message to start chatting
								</div>

								<div v-for="message in messageHistory" :class="[message.from == 'user' ? 'row item user' : 'row item bot']">
									<div v-if="message.from == 'user'" class="right profile">me</div>
									<div v-if="message.from == 'user'" class="left message">{{message.content}}</div>

									<div v-if="message.from == 'bot'" class="left profile">yoda</div>
									<div v-if="message.from == 'bot'" class="right message" v-html="message.content"></div>
								</div>

								<div v-show="messageHistory.length > 0 && messages.length > 0" class="row item prev-mess">
									<span>previous messages</span>
								</div>

								<div v-for="message in messages" :class="[message.from == 'user' ? 'row item user' : 'row item bot']">
									<div v-if="message.from == 'user'" class="right profile">me</div>
									<div v-if="message.from == 'user'" class="left message">{{message.content}}</div>

									<div v-if="message.from == 'bot'" class="left profile">yoda</div>
									<div v-if="message.from == 'bot'" class="right message" v-html="message.content"></div>
								</div>

								<div class="row item is-writing">
									<span v-show="botIsWriting">yoda is writing</span>
									<button v-show="messageHistory.length > 0" @click="clearMessage">clear mesages</button>
								</div>
							</div>
						</td>
					</tr>

					<tr class="footer">
						<td>
							<message-form @getMessage="newMessage"></message-form>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	`,

	data: function() {
		return {
			messages: [],
			messageHistory: [],
			botIsWriting: false
		}
	},

	methods: {
		newMessage(message) {
			var messageObj = {from: 'user', content: message};

			this.messages.push(messageObj);
			this.botIsWriting = true;
			this.sendMessage(message);
			this.saveMessageToHistory(messageObj);
		},

		receiveMessage(message) {
			var messageObj = {from: 'bot', content: message};

			this.messages.push(messageObj);
			this.botIsWriting = false;
			this.saveMessageToHistory(messageObj);
		},

		sendMessage(message) {
			var that = this,
				xhr = new XMLHttpRequest();

			xhr.addEventListener('load', function() {
				that.receiveMessage(xhr.responseText);
			});

			xhr.open('POST', 'conversation');
			xhr.setRequestHeader('Content-type', 'application/json');

			xhr.send(
				JSON.stringify({
					'message': message
				})
			);
		},

		saveMessageToHistory(message) {
			var msgHistory = this.getHistory();

			msgHistory.push(message);
			localStorage.setItem('bot.messages', JSON.stringify(msgHistory));
		},

		getHistory() {
			var msgHistory = localStorage.getItem('bot.messages');

			if ( msgHistory ) {
				return JSON.parse(msgHistory);	

			} else {
				localStorage.setItem('bot.messages', JSON.stringify([]));
				return [];
			}
		},

		clearMessage() {
			localStorage.setItem(
				'bot.messages', JSON.stringify([])
			);

			this.messageHistory = [];
			this.messages = [];
		}
	},

	created: function () {
		this.messageHistory = this.getHistory();
	},

	watch: {
		messages: function() {
			setTimeout(function() {
				scrollToBottom();
			}, 50);
		}
	}
})

Vue.component('message-form', {
	template: `
		<form class="m-0" @submit.prevent="send">
			<input type="text" class="u-full-width" v-model="newMessage" placeholder="Send a message" maxlength="250" autocomplete="off" autofocus>
		</form>
	`,

	data: function() {
		return {
			newMessage: ''
		}
	},

	methods: {
		send() {
			if ( this.newMessage ) {
				this.$emit('getMessage', this.newMessage);
				this.newMessage = "";
			}
		}
	}
})

var app = new Vue({
	el: '#webchat'
})
