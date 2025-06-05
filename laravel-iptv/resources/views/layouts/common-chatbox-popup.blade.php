<button class="open-ai-chatbox-popup-button shadow" onclick="openAIChatBox(event)">
    <i class="fa-solid fa-robot me-2"></i>Pilli Boy AI
</button>

<div class="ai-chat-popup" id="ai-chatbox-popup">
    <div class="chatbox-form-container">
        <div class="card shadow">
            <div class="card-header m-0 p-0" style="background-color: #15a0a3 !important;">
                <button class="btn btn-primary"><i class="fa-solid fa-robot me-2"></i>Pilli Boy AI</button>
                <button class="btn btn-primary float-end" title="Close ChatBox" onclick="closeAIChatBox()"><i class="fa fa-window-minimize" ></i></button>
            </div>
            <div class="card-body m-0 p-0">
                <iframe src="https://servicecopilot.microsoft.com/environments/5eab483f-8cd6-e43e-8eaa-2e2a1bc5eb0d/copilots/msdyn_AgentCopilot/webchat" frameborder="0" style="width: 100%; height: 100%; min-height: 400px; min-width: 250px; border-color: none;"></iframe>
            </div>
        </div>
    </div>
</div>