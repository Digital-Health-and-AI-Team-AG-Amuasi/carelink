import './bootstrap';

import Tagify from '@yaireo/tagify';
import '@yaireo/tagify/dist/tagify.css';

import $ from 'jquery';
window.$ = window.jQuery = $;

var sendContext = true; // Temporary state to

import '../metronic/core/index';
import '@keenthemes/ktui/src/index';

const defaultThemeMode = 'light'; // light|dark|system
let themeMode;

if (document.documentElement) {
    if (localStorage.getItem('kt-theme')) {
        themeMode = localStorage.getItem('kt-theme');
    } else if (
        document.documentElement.hasAttribute('data-kt-theme-mode')
    ) {
        themeMode =
            document.documentElement.getAttribute('data-kt-theme-mode');
    } else {
        themeMode = defaultThemeMode;
    }

    if (themeMode === 'system') {
        themeMode = window.matchMedia('(prefers-color-scheme: dark)').matches
            ? 'dark'
            : 'light';
    }

    document.documentElement.classList.add(themeMode);
}

document.addEventListener("DOMContentLoaded", function() {

const patient_id = document.getElementsByClassName('patient-id');
    // Get references to the button and modal
    const addNewRecordBtn = document.getElementById('addNewRecordBtn');
    const aiChatBot = document.getElementById('chat-messages');
    const addNewRecordModal = document.getElementById('addNewRecordModal');
    const closeModalBtn = document.getElementById('closeModalBtn');

    const collapsibleTriggers = document.querySelectorAll('.collapsible-trigger');

   const textarea = document.getElementById('impression');
const aiSuggestionTrigger = document.getElementById('aiSuggestionTrigger');
const aiChatPopup = document.getElementById('aiChatPopup');
const closeAiChatPopup = document.getElementById('closeAiChatPopup');

 const loginForm = document.getElementById('sign_in_form');
    const formLoader = document.getElementById('form-loader');

    if (loginForm && formLoader) {
        loginForm.addEventListener('submit', function() {
            formLoader.classList.remove('hidden');
            loginForm.querySelector('button[type="submit"]').disabled = true;
        });

        const errorAlert = document.getElementById('alert_5');
        if (errorAlert && errorAlert.querySelector('ul li')) {
            formLoader.classList.add('hidden');
            loginForm.querySelector('button[type="submit"]').disabled = false;
        }


    }
 function dragMoveListener(event) {
        const target = event.target;
        const x = (parseFloat(target.getAttribute('data-x')) || 0) + event.dx;
        const y = (parseFloat(target.getAttribute('data-y')) || 0) + event.dy;

        target.style.transform = `translate(${x}px, ${y}px)`;
        target.setAttribute('data-x', x);
        target.setAttribute('data-y', y);
    }

    interact('#aiChatPopup').draggable({
        allowFrom: '#aiChatPopupHeader',
        listeners: { move: dragMoveListener },
        modifiers: [
            interact.modifiers.restrictRect({
                restriction: {
                    left: 0,
                    right: window.innerWidth - 384,
                    top: 0,
                    bottom: window.innerHeight - document.getElementById('aiChatPopup').offsetHeight
                }
            })
        ],
        inertia: false
    });

    // Drag functionality for settings-sidebar
    interact('#settings-sidebar').draggable({
        allowFrom: '#settings-header',
        listeners: { move: dragMoveListener },
        modifiers: [
            interact.modifiers.restrictRect({
                restriction: {
                    left: 0,
                    right: window.innerWidth - 320,
                    top: 0,
                    bottom: window.innerHeight - document.getElementById('settings-sidebar').offsetHeight
                }
            })
        ],
        inertia: false
    });

    // Settings sidebar toggle
    const settingsToggle = document.getElementById('settings-toggle');
    const settingsSidebar = document.getElementById('settings-sidebar');
    const closeSettingsSidebar = document.getElementById('close-settings-sidebar');
    const cancelSettings = document.getElementById('cancel-settings');
    const saveSettings = document.getElementById('save-settings');

    function toggleSettingsSidebar() {
        if (settingsSidebar.classList.contains('translate-x-full')) {
            settingsSidebar.classList.remove('translate-x-full', 'opacity-0');
            settingsSidebar.classList.add('translate-x-0', 'opacity-100');
        } else {
            settingsSidebar.classList.remove('translate-x-0', 'opacity-100');
            settingsSidebar.classList.add('translate-x-full', 'opacity-0');
        }
    }

    if (settingsToggle) {
        settingsToggle.addEventListener('click', toggleSettingsSidebar);
    }

    if (closeSettingsSidebar) {
        closeSettingsSidebar.addEventListener('click', toggleSettingsSidebar);
    }

    if (cancelSettings) {
        cancelSettings.addEventListener('click', toggleSettingsSidebar);
    }

    if (saveSettings) {
        saveSettings.addEventListener('click', function () {
            const language = document.getElementById('language-select').value;
            const modality = document.querySelector('input[name="modality"]:checked').value;
            makeAjaxCallToPref({patientPhoneNumber, language, modality })
                .then(response => {
                    if (response.data === 'An error occurred') {
                        alert('Failed to save settings. Please try again.');
                    } else {
                        alert('Settings saved successfully!');
                    }
                });
            toggleSettingsSidebar();
        });
    }

    // Close sidebar when clicking outside, excluding record-meta
    document.addEventListener('click', function (e) {
        const isClickInsideSidebar = settingsSidebar.contains(e.target);
        const isClickOnToggle = settingsToggle.contains(e.target);
        const isClickOnRecordMeta = e.target.closest('.record-meta');
        if (!isClickInsideSidebar && !isClickOnToggle && !isClickOnRecordMeta && !settingsSidebar.classList.contains('translate-x-full')) {
            toggleSettingsSidebar();
        }
    });

    // Add/Edit Record Modal Logic

    const newRecordForm = document.getElementById('newRecordForm');
    const modalTitle = document.getElementById('modal-title');
    const formSubmitBtn = document.getElementById('form-submit-btn');
    const modalLoader = document.getElementById('modal-loader');
    const vitalsInput = document.getElementById('vitals');
newRecordForm.addEventListener('keydown', function (event) {
        // Check if Enter key is pressed
        if (event.key === 'Enter') {
            // Prevent form submission
            event.preventDefault();
        }
    });
    function openModal(mode = 'add', recordId = null, updateUrl = null) {
        addNewRecordModal.classList.remove('hidden');
        modalLoader.classList.add('hidden');

        const pat_id = document.getElementById("patient-id").value

        if (mode === 'edit' && recordId && updateUrl) {
            console.log('Opening modal for editing record:', recordId);
            modalTitle.textContent = 'Edit Record';
            formSubmitBtn.textContent = 'Update Record';
            newRecordForm.action = `/patients/patient/${pat_id}/review/${recordId}/update`;
            newRecordForm.querySelector('input[name="_method"]').value = 'PUT';
            modalLoader.classList.remove('hidden');
           fetch(`/api/patients/review/${recordId}`, {
    method: 'GET',
    headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
        'Api-Key': 'W11JUFcsYd6s80htOBy3VBpmQ0ombRSSES9kZqKFsp9qdrNzS1PDnRRh8ePD'
    }
})
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                console.log('Record data fetched successfully:', response);
                return response.json();
            })
            .then(data => {
                console.log('Record data:', data);
                document.getElementById('current_complaints').value = data.current_complains || '';
                document.getElementById('hpc').value = data.history_presenting_complains || '';
                document.getElementById('on_direct_questions').value = data.on_direct_questions || '';
                document.getElementById('on_examinations').value = data.on_examinations || '';
                document.getElementById('investigations').value = data.labs || '';
                document.getElementById('impression').value = data.impression || '';
                document.getElementById('plan').value = data.plan || '';
                populateTagify('.tagify-field', data.vitals || []);

                modalLoader.classList.add('hidden');
            })
            .catch(error => {
                console.error('Error fetching record:', error);
                modalLoader.classList.add('hidden');
                alert('Failed to load record data. Please try again.');
            });
        } else {
            modalTitle.textContent = 'Add New Record';
            formSubmitBtn.textContent = 'Save Record';
            newRecordForm.action = `/patients/${pat_id}/review`;
            newRecordForm.querySelector('input[name="_method"]').value = 'POST';
            newRecordForm.reset();
             newRecordForm.querySelectorAll('.tagify-field').forEach(field => {
        if (field.tagify) {
            field.tagify.removeAllTags();
        }
    });
        }
    }

    function closeModal() {
        addNewRecordModal.classList.add('hidden');
        newRecordForm.reset();
        if (vitalsInput.tagify) {
            vitalsInput.tagify.removeAllTags();
        }
    }

    if (addNewRecordBtn) {
        addNewRecordBtn.addEventListener('click', () => openModal('add'));
    }

    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', closeModal);
    }

    // Event delegation for edit buttons
    document.querySelector('.min-h-screen').addEventListener('click', function (e) {
        const btn = e.target.closest('.edit-record-btn');
        if (btn) {
            const container = btn.closest('.record-meta');
            if (container) {
                const recordId = container.querySelector('.record-id')?.value;
                const updateUrl = container.querySelector('.update-url')?.value;
                if (recordId && updateUrl) {
                    openModal('edit', recordId, updateUrl);
                } else {
                    console.error('Missing record-id or update-url in record-meta');
                    alert('Error: Record data is incomplete.');
                }
            } else {
                console.error('record-meta container not found');
                alert('Error: Record container not found.');
            }
        }
    });

    document.addEventListener('click', function (e) {
        if (e.target === addNewRecordModal) {
            closeModal();
        }
    });

    async function makeAjaxCallToPref(payload, endpoint = 'https://care-ai-backend-new-csg6agb2g8fjb0f8.canadacentral-01.azurewebsites.net/update-patient-preferences') {
        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(payload)
            });

            const data = await response.json();

            if (!data.success) {
                console.error('Error:', data.message);
                return { data: 'An error occurred' };
            }

            return data;
        } catch (error) {
            console.error('Error:', error);
            return { data: 'An error occurred' };
        }
    }
// --- Show/Hide AI Trigger Icon ---
textarea.addEventListener('input', function () {
    if (textarea.value.length > 0) {
        aiSuggestionTrigger.classList.remove('hidden', 'opacity-0');
        aiSuggestionTrigger.classList.add('opacity-100');
    } else {
        aiSuggestionTrigger.classList.add('opacity-0');
        setTimeout(() => {
            if (textarea.value.length === 0) {
                aiSuggestionTrigger.classList.add('hidden');
            }
        }, 300);
    }
});
 if (addNewRecordBtn && addNewRecordModal) {
        addNewRecordBtn.addEventListener('click', async function () {
            showModal(addNewRecordModal);
            try {
                console.log("Generating patient issues and updates...");
                await generatePatientIssuesAndUpdates();

            } catch (error) {
                console.error("Error generating patient issues and updates:", error);
            }
        });
    }
const acceptBtn = document.getElementById('acceptAiPrompt');
    const declineBtn = document.getElementById('declineAiPrompt');
    const aiPrompt = document.getElementById('aiPrompt');
    const aiImpression = document.getElementById('aiImpression');
    const aiPlan = document.getElementById('aiPlan');
    const showPlanBtn = document.getElementById('showPlanSuggestion');
    const closeBtn = document.getElementById('closeAiChatPopup');
    const formDataAi = document.getElementById('form-loader-ai');

    const loaderSpinner = document.querySelector('.loader-overlay');

 formDataAi.classList.remove('loader-overlay');
loaderSpinner.classList.add('hidden');
    acceptBtn.addEventListener('click', async () => {
        formDataAi.classList.add('loader-overlay');

        loaderSpinner.classList.remove('hidden');
        await generateAiRecommendations();
    });

    declineBtn.addEventListener('click', async () => {
        formDataAi.classList.add('loader-overlay');
        formDataAi.classList.add('no');
        loaderSpinner.classList.remove('hidden');
         await generateAiRecommendations();
    });

    showPlanBtn.addEventListener('click', () => {
        aiPlan.classList.remove('hidden');
        showPlanBtn.classList.add('hidden');
    });

    closeBtn.addEventListener('click', () => {
        document.getElementById('aiChatPopup').classList.add('hidden');
    });
// --- Open AI Chat Pop-up ---
aiSuggestionTrigger.addEventListener('click', function () {
    aiChatPopup.classList.remove('hidden');

    const rect = textarea.getBoundingClientRect();
    aiChatPopup.style.left = `${rect.right + 10}px`;
    aiChatPopup.style.top = `${rect.top + window.scrollY}px`; // consider scroll
});

// --- Close AI Chat Pop-up ---
closeAiChatPopup.addEventListener('click', function () {
    aiChatPopup.classList.add('hidden');
});



function dragElement(elmnt) {
    let pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
    let isDragging = false;

    const header = document.getElementById(elmnt.id + "Header");
    const dragTarget = header || elmnt;

    dragTarget.addEventListener('mousedown', dragMouseDown);

    function dragMouseDown(e) {
        e.preventDefault();
        pos3 = e.clientX;
        pos4 = e.clientY;
        isDragging = true;

        document.addEventListener('mouseup', closeDragElement);
        document.addEventListener('mousemove', elementDrag);
    }

    function elementDrag(e) {
        if (!isDragging) return;

        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;

        // Use requestAnimationFrame for smoother movement
        requestAnimationFrame(() => {
            elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
            elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
        });
    }

    function closeDragElement() {
        isDragging = false;
        document.removeEventListener('mouseup', closeDragElement);
        document.removeEventListener('mousemove', elementDrag);
    }
}




    collapsibleTriggers.forEach(trigger => {
        trigger.addEventListener('click', function () {
            const targetId = this.dataset.target;
            const content = document.getElementById(targetId);
            const chevron = this.querySelector('svg');

            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                chevron.style.transform = 'rotate(90deg)';
            } else {
                content.classList.add('hidden');
                chevron.style.transform = 'rotate(0deg)';
            }
        });
    });

    const toggleAISuggestionsBtn = document.getElementById('toggle-ai-suggestions-btn');
    const closeAISuggestionsBtn = document.getElementById('close-ai-suggestions-btn');
//  addNewRecordBtn.addEventListener('click', function () {
//     if (aiChatBot) {
//         (async function () {
//             await generatePatientIssuesAndUpdates();
//         })();
//     }
// });




document.getElementById('toggle-ai-fetch').addEventListener('click', async function () {
    const toggle = this;
    const knob = toggle.querySelector('span');
    const isActive = toggle.classList.contains('bg-green-500');
    const hpcTextarea = document.getElementById('hpc');

    if (isActive) {
        // Turn OFF
        toggle.classList.remove('bg-green-500');
        toggle.classList.add('bg-gray-300');
        knob.classList.remove('translate-x-6');
        knob.classList.add('translate-x-1');

        hpcTextarea.value = '';
        hpcTextarea.placeholder = 'History of Presenting Complaint (HPC) will be written manually.';
        console.log('AI OFF – Doctor will write manually');
    } else {
        // Turn ON
toggle.classList.remove('bg-gray-300');
        toggle.classList.add('bg-green-500');
        knob.classList.remove('translate-x-1');
        knob.classList.add('translate-x-6');

        console.log('AI ON – Generating HPC...');
         const HPC = document.getElementById('hpc');
    const phoneElement = document.getElementById('record-data-phone');
    if (!phoneElement) return;

    const patientPhoneNumber = phoneElement.dataset.phone;
    HPC.placeholder = 'Getting History of Presenting Complaint...';

    try {
        const payload = {
            send_chat_as_context: true,
            send_patient_profile: false,
            required_assistant: "cds_patient_issues_assistant",
            patient_phone_number: patientPhoneNumber
        };

        const response = await makeAjaxCall(payload);
        const result = await response.data;

        if (result?.data) {
            const issues = result.data.patient_issues || "No issues returned.";
            const updates = result.data.patient_updates || "No updates returned.";


            HPC.value = issues || "No HPC returned.";


            renderMessages(chatHistory);
        } else {
            console.log("Unexpected response structure:", result?.success);
            const errMsg = "Could not retrieve patient data.";

        }
    } catch (error) {
        const errorMsg = `Error: ${error.message}`;
        chatHistory.push({ text: errorMsg, from: "ai" });
        unreadCount += 1;
        triggerNotification();
        renderMessages(chatHistory);
        console.error("AI fetch error:", error);
    }

    }
});



    // Add event listeners
    if (toggleAISuggestionsBtn) {
        toggleAISuggestionsBtn.addEventListener('click', async () => {
            try {
                await toggleAISuggestionsModal();
            } catch (error) {
                // Handle the error gracefully
                alert(`Sorry, something happened: ${error.message}`);
                console.error("Failed to toggle AI suggestions modal:", error);
            }
        });
    }
    if (closeAISuggestionsBtn) {
        closeAISuggestionsBtn.addEventListener('click', toggleAISuggestionsModal);
    }

    if(addNewRecordBtn){
        addNewRecordBtn.addEventListener('click',async function
            () {
            showModal(addNewRecordModal)
 (async function () {
console.log("Generating patient issues and updates...");
            await generatePatientIssuesAndUpdates();
        })();

        });
    }
if (closeModalBtn) {
    closeModalBtn.addEventListener('click', function () {
        hideModal(addNewRecordModal);
        resetFormAndTagify(); // Clear the field when modal is closed
    });
}




    // // Close modal when clicking outside of it
    // if(addNewRecordModal)
    //     addNewRecordModal.addEventListener('click', function(event) {
    //     if (event.target === addNewRecordModal) {
    //         hideModal(addNewRecordModal);
    //     }
    // });

  const tagifyInstances = new Map();

function initializeTagify(inputId, options = {}) {
    const input = document.getElementById(inputId);

    if (!input) return;

    // Destroy existing instance if it exists
    if (tagifyInstances.has(input)) {
        tagifyInstances.get(input).destroy();
    }

    const tagify = new Tagify(input, {
        duplicates: false,
        dropdown: {
            enabled: 0
        },
        ...options
    });

    tagifyInstances.set(input, tagify);
    return tagify;
}

function resetFormAndTagify() {
    const input = document.getElementById('vitals');

    if (tagifyInstances.has(input)) {
        tagifyInstances.get(input).destroy();
        tagifyInstances.delete(input);
    }

    input.value = ''; // or whatever default

    initializeTagify('vitals');
}
resetFormAndTagify();

function populateTagify(selector, tagsData) {
    console.log("Populating Tagify for selector:", selector, "with data:", tagsData);
    const input = document.querySelector(selector);
    if (!input) return;

    let tagify = tagifyInstances.get(input);

    if (!tagify) {
        tagify = new Tagify(input, {
            duplicates: false,
            dropdown: {
                enabled: 0
            }
        });
        tagifyInstances.set(input, tagify);
    }

    tagify.removeAllTags();

    if (typeof tagsData === 'string') {
        try {
            tagsData = JSON.parse(tagsData);
        } catch (e) {
            console.error("Invalid JSON string:", tagsData);
            return;
        }
    }

    if (tagsData && !Array.isArray(tagsData)) {
        tagsData = [tagsData];
    }

    if (Array.isArray(tagsData)) {
        tagify.addTags(tagsData);
    }
}

    const sendLink = document.getElementById("sendLink");
    const startChat = document.getElementById("start_chat_button");
    const generateAiContentBtn = document.getElementById('generateAiContentBtn');
    const submitAiContentBtn = document.getElementById('submitAiContentBtn');
    const clearAiContentBtn = document.getElementById('clearAiContentBtn'); // Get the new clear button

    document.querySelectorAll('.record-data').forEach(container => {
        const recordId = container.dataset.id;

        // View Record Toggle
        const viewRecordBtn = container.querySelector(`.view-record-btn-${recordId}`);
        const recordContent = document.getElementById(`record-content-${recordId}`);

        if (viewRecordBtn && recordContent) {
            viewRecordBtn.addEventListener('click', function () {
                const isHidden = recordContent.classList.toggle('hidden');
                this.classList.toggle('active', !isHidden);
            });
        }

        // Generate AI Content
        const generateAiContentBtn = container.querySelector(`#generate-ai-content-btn-${recordId}`);

        const impressionsFinalTextarea = document.getElementById(`impressionsFinalTextarea-${recordId}`);
        const plansFinalTextarea = document.getElementById(`plansFinalTextarea-${recordId}`);

        if (generateAiContentBtn) {
            generateAiContentBtn.addEventListener('click', async function () {
                this.disabled = true;
                await generateAiRecommendations(recordId);
                this.disabled = false;
            });
        }
    });

    // sendLink.addEventListener("click", async function(event) {
    //     event.preventDefault();
    //
    //     await handleSendBtnClick()
    // });
    //
    // startChat.addEventListener("click", async function(event) {
    //     await handleSendContext()
    // });

    // if(generateAiContentBtn){
    //     generateAiContentBtn.addEventListener('click', async () => {
    //
    //         generateAiContentBtn.disabled = true; // Disable button during generation
    //         await generateAiRecommendations(aiContentTextarea)
    //         generateAiContentBtn.disabled = false; // Re-enable button
    //     });
    // }

    if(submitAiContentBtn){
        submitAiContentBtn.addEventListener('click', submitAiContent);
    }

    if(clearAiContentBtn){
        clearAiContentBtn.addEventListener('click', clearAiContent);
    }

});

// Function to show the modal
function showModal(addNewRecordModal) {
    addNewRecordModal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

// Function to hide the modal
function hideModal(addNewRecordModal) {
    addNewRecordModal.classList.add('hidden');
    document.body.style.overflow = 'auto'; // Re-enable scrolling
}

function submitAiContent(aiContentTextarea){

    const content = aiContentTextarea.value;
    if (content.trim() === '') {
        // Using custom modal instead of alert
        const messageBox = document.createElement('div');
        messageBox.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50';
        messageBox.innerHTML = `
                        <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm mx-auto text-center">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">Cannot Submit</h3>
                            <p class="text-sm text-gray-600 mb-6">AI content area is empty. Please generate content first.</p>
                            <button id="closeMessageBox" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">OK</button>
                        </div>
                    `;
        document.body.appendChild(messageBox);
        document.getElementById('closeMessageBox').addEventListener('click', () => {
            document.body.removeChild(messageBox);
        });
        return;
    }
    console.log('Submitting AI generated content:', content);
    // Using custom modal instead of alert
    const messageBox = document.createElement('div');
    messageBox.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50';
    messageBox.innerHTML = `
                    <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm mx-auto text-center">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Submission Successful</h3>
                        <p class="text-sm text-gray-600 mb-6">AI content submitted successfully!</p>
                        <button id="closeMessageBox" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">OK</button>
                    </div>
                `;
    document.body.appendChild(messageBox);
    document.getElementById('closeMessageBox').addEventListener('click', () => {
        document.body.removeChild(messageBox);
    });
    aiContentTextarea.value = ''; // Clear the textarea after submission
}

function clearAiContent(){
    const aiContentTextarea = document.getElementById('aiContentTextarea');

    aiContentTextarea.value = ''; // Clear the text area
    // Optional: Show a confirmation message that the text has been cleared
    const messageBox = document.createElement('div');
    messageBox.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full flex items-center justify-center z-50';
    messageBox.innerHTML = `
                    <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm mx-auto text-center">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Content Cleared</h3>
                        <p class="text-sm text-gray-600 mb-6">The AI content area has been cleared.</p>
                        <button id="closeMessageBox" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50">OK</button>
                    </div>
                `;
    document.body.appendChild(messageBox);
    document.getElementById('closeMessageBox').addEventListener('click', () => {
        document.body.removeChild(messageBox);
    });
}


async function handleSendContext(){

    if(!sendContext)
        return

    let chatBox = document.getElementById('chatBox');

    let wrapper = createChatMessage("", 'bot-typing');
    chatBox.appendChild(wrapper);
    chatBox.scrollTop = chatBox.scrollHeight;

    const payload = {
        doctor_query: null,
        patient_phone_number: "0589420384",
        send_chat_as_context: sendContext  // Whether to send patient data
    }

    const ajaxCallResponse = await makeAjaxCall(payload)
    sendContext = false

    chatBox.removeChild(wrapper);

    wrapper = createChatMessage(ajaxCallResponse.data, 'bot')

    chatBox.appendChild(wrapper);
    chatBox.scrollTop = chatBox.scrollHeight;
}

async function handleSendBtnClick(){
    let inputField = document.getElementById('questionInput');
    let messageText = inputField.value.trim();
    let chatBox = document.getElementById('chatBox');

    if (!messageText){
        alert('Please enter a message before sending.');
        inputField.focus();
        return;
    }

    let wrapper = createChatMessage(messageText, 'human');

    chatBox.appendChild(wrapper);
    inputField.value = '';
    chatBox.scrollTop = chatBox.scrollHeight;

    wrapper = createChatMessage("", 'bot-typing');
    chatBox.appendChild(wrapper);
    chatBox.scrollTop = chatBox.scrollHeight;

    const payload = {
        doctor_query: messageText,
        patient_phone_number: "0589420384",
        send_context: false  // Whether to send patient data
    }

    const ajaxCallResponse = await makeAjaxCall(payload)

    chatBox.removeChild(wrapper);

    wrapper = createChatMessage(ajaxCallResponse.data, 'bot')

    chatBox.appendChild(wrapper);
    chatBox.scrollTop = chatBox.scrollHeight;
}

function createChatMessage(messageText, messageType, timestamp = null) {

    const wrapper = document.createElement('div');
    wrapper.className = "flex items-end gap-3.5 px-5";

    if (messageType === 'human')
        wrapper.classList.add('justify-end')


    const avatar = document.createElement('img');
    avatar.src = "assets/media/avatars/300-5.png";
    avatar.alt = "";
    avatar.className = "rounded-full size-9";

    wrapper.append(avatar)

    const messageWrapper = document.createElement('div');
    messageWrapper.className = "flex flex-col gap-1.5";

    const messageBubble = document.createElement('div');

    if (messageType === 'bot'){
        messageBubble.className = "kt-card shadow-none flex flex-col bg-accent/60 gap-2.5 p-3 rounded-bs-none text-2sm";
        messageBubble.textContent = messageText
    }else if(messageType === 'bot-typing'){
        messageBubble.className = "kt-card shadow-none flex flex-col bg-accent/60 gap-2.5 p-3 rounded-bs-none text-2sm typing-animation";
        let typingMessage = document.createElement('div');
        typingMessage.classList.add('typing-animation');
        typingMessage.innerHTML = '<span></span><span></span><span></span>';
        messageBubble.append(typingMessage)
    }else if(messageType === 'human'){
        messageBubble.className = "kt-card shadow-none flex bg-primary flex-col gap-2.5 p-3 rounded-be-none";

        const paragraph = document.createElement('p');
        paragraph.className = 'text-2sm font-medium text-primary-foreground'
        paragraph.textContent = messageText

        messageBubble.append(paragraph)
    }

    const time = document.createElement('span');
    time.className = "text-xs font-medium text-secondary-foreground";

    const now = new Date();
    time.textContent = timestamp || `${now.getHours().toString().padStart(2, '0')}:${now.getMinutes().toString().padStart(2, '0')}`;

    messageWrapper.appendChild(messageBubble);
    messageWrapper.appendChild(time);
    wrapper.appendChild(messageWrapper);

    return wrapper
}

async function makeAjaxCall(payload, endpoint = '/ask-llm') {
    try {
        const response = await fetch(endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(payload)
        });

        const data = await response.json();

        if (!data.success) {
            console.error('Error:', data.message);
            return {
                data: 'An error occurred'
            };
        }

        return data;

    } catch (error) {
        console.error('Error:', error);
        return {
            data: 'An error occurred'
        };
    }
}
 let unreadCount = 0;
let chatVisible = false;
let chatHistory = [];
async function generateAiRecommendations() {
    const impressionsAiContentTextarea = document.getElementById('ai-suggestion-impression-modal');
    const plansAiContentTextarea = document.getElementById('ai-suggestion-plan-modal');
    const formDataAi = document.getElementById('form-loader-ai');
    const patientPhoneNumber = document.getElementById('record-data-phone').dataset.phone;

    // Clear previous content
    impressionsAiContentTextarea.value = '';
    plansAiContentTextarea.value = '';
    impressionsAiContentTextarea.placeholder = 'Asking CARE AI...';
    plansAiContentTextarea.placeholder = 'Asking CARE AI...';

    // Determine if chat context is checked
    const isChecked = formDataAi.classList.contains('loader-overlay') && !formDataAi.classList.contains('no');

    // Collect form data
    const records = [{
        issues: document.getElementById('hpc').value,
        current_complains: document.getElementById('current_complaints').value,
        on_direct_questions: document.getElementById('on_direct_questions').value,
        on_examinations: document.getElementById('on_examinations').value,
        vitals: document.getElementById('vitals').value,
        investigations: document.getElementById('investigations').value,
    }];

    const payload = {
        send_chat_as_context: isChecked,
        send_patient_profile: true,
        required_assistant: 'management_plan_assistant',
        patient_review_record_id: null,
        patient_phone_number: patientPhoneNumber,
        records: records
    };

    try {
        const response = await makeAjaxCall(payload);
        console.log("AI Recommendations Response:", response);
        if (!response || !response.data.data || !response.data.data.impressions || !response.data.data.plans) {
            throw new Error("Invalid response format");
        }
console.log("AI Recommendations Data:", response);
        const result = response.data.data;
        console.log("AI Recommendations:", result);
        // Hide loading overlay and open AI chat popup
        document.getElementById('aiChatPopup').classList.add('hidden');
        openChatPopup();

        // Build chat history
        chatHistory = [
            { type: "section", text: "AI Impressions" },
            { text: result.impressions, from: "ai" },
            { type: "section", text: "AI Plans" },
            { text: result.plans, from: "ai" }
        ];

        // Show typing indicator
        const typingIndicator = document.getElementById("typing-indicator");
        typingIndicator.classList.remove("hidden");

        // Render results after delay
        setTimeout(() => {
            renderMessages(chatHistory);
            formDataAi.classList.remove('loader-overlay', 'no');
            formDataAi.classList.add('hidden');
        }, 1000);

        // Set content into textareas
        impressionsAiContentTextarea.textContent = result.impressions;
        plansAiContentTextarea.textContent = result.plans;

    } catch (error) {
        // Handle all errors in one place
        console.error('Error generating AI recommendations:', error);
        document.getElementById('aiChatPopup').classList.add('hidden');
        impressionsAiContentTextarea.placeholder = `Error generating content: ${error.message}`;
        plansAiContentTextarea.placeholder = `Error generating content: ${error.message}`;
    }
}



 function openChatPopup(force = false) {
    const chatPopup = document.getElementById("chat-popup");
    if (!chatPopup) return;


    if (force || !chatPopup.classList.contains("translate-y-0")) {
        chatPopup.classList.remove("translate-y-full", "opacity-0");
        chatPopup.classList.add("translate-y-0", "opacity-100");
        chatVisible = true;
        clearNotification();
    }
}

// Modified triggerNotification to auto-open on new messages
function triggerNotification() {
    const badge = document.getElementById("notification-badge");
    const chatButton = document.getElementById("notification-button");

    if (!chatVisible && unreadCount > 0) {
        console.log("Triggering notification with unread count:", unreadCount);
        badge.textContent = unreadCount;
        badge.classList.remove("hidden", "opacity-0", "scale-0");
        badge.classList.add("flex", "opacity-100", "scale-100");
        chatButton.classList.remove("hidden");
        chatButton.classList.add("fixed");

        // setTimeout(() => {
        //     openChatPopup();
        // }, 500);
    }
}


function clearNotification() {
    unreadCount = 0;
    const badge = document.getElementById("notification-badge");
    badge.classList.add("hidden");
    badge.classList.remove("flex");
}

function renderMessages(chatHistory) {
    const messages = document.getElementById("chat-messages");
    const typingIndicator = document.getElementById("typing-indicator");

    if (!messages) return;

    messages.innerHTML = "";

    let delay = 0;

    chatHistory.forEach((m, index) => {
        setTimeout(() => {
            if (m.type === "section") {
                const divider = document.createElement("div");
                divider.className = "flex items-center justify-center my-4 px-5";
                divider.innerHTML = `
                    <div class="border-t border-gray-200 flex-grow mr-3"></div>
                    <span class="text-sm font-medium text-gray-600 uppercase tracking-widest bg-gray-50 px-4 py-1.5 rounded-full shadow-sm">${m.text}</span>
                    <div class="border-t border-gray-200 flex-grow ml-3"></div>
                `;
                messages.appendChild(divider);
            } else {
                const isUser = m.from === "user";

                const bubbleWrapper = document.createElement("div");
                bubbleWrapper.className = `flex ${isUser ? "justify-end" : "justify-start"} items-end gap-3 w-full px-4 mb-4 animate-slide-in`;

                const bubble = document.createElement("div");
                bubble.className = `
                    ${isUser ? "bg-green-500 text-white" : "bg-white border border-gray-200 text-gray-900"}
                    px-5 py-3 text-base max-w-[95%] rounded-xl
                    ${isUser ? "rounded-br-none" : "rounded-bl-none"}
                    shadow-md whitespace-pre-line leading-loose break-words transition-all duration-200
                `.trim();

                bubble.textContent = m.text;



                bubbleWrapper.appendChild(bubble);
                messages.appendChild(bubbleWrapper);
            }

            messages.scrollTop = messages.scrollHeight;
        }, delay);

        delay += 250; // Slightly increased delay for smoother pacing
    });

    // Hide typing indicator after all messages shown
    setTimeout(() => {
        if (typingIndicator) typingIndicator.classList.add("hidden");
    }, delay + 150);
}


async function generatePatientIssuesAndUpdates() {
    const HPC = document.getElementById('hpc');
    const phoneElement = document.getElementById('record-data-phone');
    if (!phoneElement) return;

    const patientPhoneNumber = phoneElement.dataset.phone;


    try {
        const payload = {
            send_chat_as_context: true,
            send_patient_profile: false,
            required_assistant: "cds_patient_issues_assistant",
            patient_phone_number: patientPhoneNumber
        };

        const response = await makeAjaxCall(payload);
        const result = await response.data;

        if (result?.data) {
            const issues = result.data.patient_issues || "No issues returned.";
            const updates = result.data.patient_updates || "No updates returned.";

            chatHistory = [];
         chatHistory.push({ type: "section", text: "Issues" });
chatHistory.push({ text: issues, from: "ai" });

chatHistory.push({ type: "section", text: "Updates" });
chatHistory.push({ text: updates, from: "ai" });


            unreadCount += 2;
            triggerNotification();
const typingIndicator = document.getElementById("typing-indicator");
typingIndicator.classList.remove("hidden");

setTimeout(() => {
    renderMessages(chatHistory);
}, 1000);

        } else {
            console.log("Unexpected response structure:", result?.success);
            const errMsg = "Could not retrieve patient data.";
            chatHistory.push({ text: errMsg, from: "ai" });
                  console.log("Triggering notification with unread count:", unreadCount);
        badge.textContent = unreadCount;
        badge.classList.remove("hidden", "opacity-0", "scale-0");
        badge.classList.add("flex", "opacity-100", "scale-100");
        chatButton.classList.remove("hidden");
        chatButton.classList.add("fixed");

        setTimeout(() => {
            openChatPopup();
        }, 500);
            const typingIndicator = document.getElementById("typing-indicator");
typingIndicator.classList.remove("hidden");

setTimeout(() => {
    renderMessages(chatHistory);
}, 1000);
        }
    } catch (error) {
        const errorMsg = `Error: ${error.message}`;
        chatHistory.push({ text: errorMsg, from: "ai" });
        unreadCount += 1;
        triggerNotification();
        const typingIndicator = document.getElementById("typing-indicator");
typingIndicator.classList.remove("hidden");

setTimeout(() => {
    renderMessages(chatHistory);
}, 1000);
    }
}




document.addEventListener('DOMContentLoaded', function () {
    const chatButton = document.getElementById('notification-button');
    const chatPopup = document.getElementById('chat-popup');
    const closeChatButton = document.getElementById('close-chat-button');

    function toggleChatPopup() {
        const isOpen = chatPopup.classList.contains('translate-y-0');

        if (isOpen) {
            chatPopup.classList.remove('translate-y-0', 'opacity-100');
            chatPopup.classList.add('translate-y-full', 'opacity-0');
        } else {
            chatPopup.classList.remove('translate-y-full', 'opacity-0');
            chatPopup.classList.add('translate-y-0', 'opacity-100');
        }
    }

    // Toggle popup when chat button is clicked
    chatButton.addEventListener('click', function (e) {
        e.stopPropagation(); // Prevent it from triggering the document click
        toggleChatPopup();
    });

    // Close when clicking outside the popup
if (chatPopup) {
        document.addEventListener('click', function (e) {
            const isClickInsidePopup = chatPopup.contains(e.target);
            const isClickOnButton = chatButton && chatButton.contains(e.target);
            const isClickInsideModal = addNewRecordModal && addNewRecordModal.contains(e.target);
            if (!isClickInsidePopup && !isClickOnButton && !isClickInsideModal) {
                chatPopup.classList.remove('translate-y-0', 'opacity-100');
                chatPopup.classList.add('translate-y-full', 'opacity-0');
                chatVisible = false;
            }
        });
    }

    // Close when close button is clicked
    closeChatButton.addEventListener('click', function () {
        chatPopup.classList.remove('translate-y-0', 'opacity-100');
        chatPopup.classList.add('translate-y-full', 'opacity-0');
    });
});







// Toggle the visibility of the AI suggestions modal
async function toggleAISuggestionsModal() {
    const aiSuggestionsModal = document.getElementById('ai-suggestions-modal');

    if (aiSuggestionsModal) {
        aiSuggestionsModal.classList.toggle('hidden');
        // Update button text based on modal visibility
        const isHidden = aiSuggestionsModal.classList.contains('hidden');

        if(!isHidden)
            await generateAiRecommendations(1);

    }

}

