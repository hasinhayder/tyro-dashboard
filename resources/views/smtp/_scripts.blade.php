<script>
(function(){
    var csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    var routes = {
        update: @json(route($dashboardRoute::name('settings.smtp.update'))),
        clearCache: @json(route($dashboardRoute::name('settings.smtp.clear-config-cache'))),
        test: @json(route($dashboardRoute::name('settings.smtp.test'))),
        presetStore: @json(route($dashboardRoute::name('settings.smtp.presets.store'))),
        presetUpdate: function(id){ return @json(route($dashboardRoute::name('settings.smtp.presets.update'), ['id' => ':id'])).replace(':id', id); },
        presetDestroy: function(id){ return @json(route($dashboardRoute::name('settings.smtp.presets.destroy'), ['id' => ':id'])).replace(':id', id); },
        presetApply: function(id){ return @json(route($dashboardRoute::name('settings.smtp.presets.apply'), ['id' => ':id'])).replace(':id', id); },
    };
    var presetsData = @json($presets->keyBy('id'));

    function headers(){ return { 'X-CSRF-TOKEN': csrf, 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json', 'Content-Type': 'application/json' }; }
    function setBusy(btn,busy){ if(!btn) return; btn.disabled=busy; btn.style.opacity=busy?'0.6':''; btn.style.cursor=busy?'wait':''; }

    window.saveSmtp = function(){
        var btn=document.getElementById('smtpSaveBtn'); setBusy(btn,true);
        fetch(routes.update,{method:'POST',headers:headers(),body:JSON.stringify({
            MAIL_MAILER: document.getElementById('MAIL_MAILER').value,
            MAIL_HOST: document.getElementById('MAIL_HOST').value,
            MAIL_PORT: document.getElementById('MAIL_PORT').value ? parseInt(document.getElementById('MAIL_PORT').value,10) : null,
            MAIL_SCHEME: document.getElementById('MAIL_SCHEME').value || null,
            MAIL_USERNAME: document.getElementById('MAIL_USERNAME').value || null,
            MAIL_PASSWORD: document.getElementById('MAIL_PASSWORD').value || null,
            MAIL_FROM_ADDRESS: document.getElementById('MAIL_FROM_ADDRESS').value || null,
            MAIL_FROM_NAME: document.getElementById('MAIL_FROM_NAME').value || null
        })})
        .then(function(r){return r.json().then(function(d){return {ok:r.ok,d:d};});})
        .then(function(res){
            if(res.ok && res.d.success){ showToast(res.d.message,'success'); setTimeout(function(){location.reload();},800); }
            else {
                var msg=res.d.message||'Failed to save.';
                if(res.d.errors){ msg=Object.values(res.d.errors).flat().join(' '); }
                showToast(msg,'error');
            }
        }).catch(function(){showToast('Network error.','error');}).finally(function(){setBusy(btn,false);});
    };

    window.clearSmtpCache = function(){
        showDanger('Clear Config Cache','Clear the config cache? This runs config:clear and reloads settings from .env.',{confirmText:'Clear Cache'})
        .then(function(ok){
            if(!ok) return;
            fetch(routes.clearCache,{method:'POST',headers:headers()})
            .then(function(r){return r.json();}).then(function(d){showToast(d.message,d.success?'success':'warning');})
            .catch(function(){showToast('Network error.','error');});
        });
    };

    window.sendTestEmail = function(){
        var btn=document.getElementById('smtpTestBtn');
        var to=document.getElementById('smtpTestTo').value.trim();
        if(!to){ showToast('Enter a recipient email.','warning'); return; }
        setBusy(btn,true);
        fetch(routes.test,{method:'POST',headers:headers(),body:JSON.stringify({to:to})})
        .then(function(r){return r.json().then(function(d){return {ok:r.ok,d:d};});})
        .then(function(res){
            if(res.ok && res.d.success) showToast(res.d.message,'success');
            else {
                var msg=res.d.message||'Failed to send.';
                if(res.d.errors) msg=Object.values(res.d.errors).flat().join(' ');
                showToast(msg,'error');
            }
        }).catch(function(){showToast('Network error.','error');}).finally(function(){setBusy(btn,false);});
    };

    window.openPresetModal = function(prefill){
        document.getElementById('presetId').value='';
        document.getElementById('presetModalTitle').textContent='Add Preset';
        document.getElementById('presetPasswordHint').textContent='Stored encrypted.';
        if(prefill){
            document.getElementById('preset_name').value='';
            document.getElementById('preset_mailer').value=document.getElementById('MAIL_MAILER').value;
            document.getElementById('preset_host').value=document.getElementById('MAIL_HOST').value;
            document.getElementById('preset_port').value=document.getElementById('MAIL_PORT').value;
            document.getElementById('preset_encryption').value=document.getElementById('MAIL_SCHEME').value;
            document.getElementById('preset_username').value=document.getElementById('MAIL_USERNAME').value;
            document.getElementById('preset_password').value='';
            document.getElementById('preset_from_address').value=document.getElementById('MAIL_FROM_ADDRESS').value;
            document.getElementById('preset_from_name').value=document.getElementById('MAIL_FROM_NAME').value;
        } else {
            document.getElementById('presetForm').reset();
            document.getElementById('preset_port').value='587';
            document.getElementById('preset_mailer').value='smtp';
        }
        openModal('smtpPresetModal');
    };

    window.saveCurrentAsPreset = function(){
        document.getElementById('presetId').value='';
        document.getElementById('presetModalTitle').textContent='Save current settings as preset';
        document.getElementById('presetPasswordHint').textContent='Stored encrypted. Leave blank if no password.';
        document.getElementById('preset_name').value='';
        document.getElementById('preset_mailer').value=document.getElementById('MAIL_MAILER').value;
        document.getElementById('preset_host').value=document.getElementById('MAIL_HOST').value;
        document.getElementById('preset_port').value=document.getElementById('MAIL_PORT').value;
        document.getElementById('preset_encryption').value=document.getElementById('MAIL_SCHEME').value;
        document.getElementById('preset_username').value=document.getElementById('MAIL_USERNAME').value;
        document.getElementById('preset_password').value='';
        document.getElementById('preset_from_address').value=document.getElementById('MAIL_FROM_ADDRESS').value;
        document.getElementById('preset_from_name').value=document.getElementById('MAIL_FROM_NAME').value;
        openModal('smtpPresetModal');
        setTimeout(function(){ var el=document.getElementById('preset_name'); if(el) el.focus(); }, 120);
    };

    window.closePresetModal = function(){ closeModal('smtpPresetModal'); };
    window.openUsePresetModal = function(){ openModal('smtpUsePresetModal'); };
    window.closeUsePresetModal = function(){ closeModal('smtpUsePresetModal'); };

    window.editPreset = function(id){
        var p=presetsData[id];
        if(!p){ showToast('Preset not found.','error'); return; }
        closeUsePresetModal();
        document.getElementById('presetId').value=p.id;
        document.getElementById('presetModalTitle').textContent='Edit Preset';
        document.getElementById('preset_name').value=p.name;
        document.getElementById('preset_mailer').value=p.mailer;
        document.getElementById('preset_host').value=p.host;
        document.getElementById('preset_port').value=p.port||'';
        document.getElementById('preset_encryption').value=p.encryption||'';
        document.getElementById('preset_username').value=p.username||'';
        document.getElementById('preset_password').value='';
        document.getElementById('presetPasswordHint').textContent='Leave blank to keep existing password.';
        document.getElementById('preset_from_address').value=p.from_address||'';
        document.getElementById('preset_from_name').value=p.from_name||'';
        setTimeout(function(){ openModal('smtpPresetModal'); }, 180);
    };

    window.savePreset = function(){
        var btn=document.getElementById('presetSaveBtn'); setBusy(btn,true);
        var id=document.getElementById('presetId').value;
        var payload={
            name: document.getElementById('preset_name').value.trim(),
            mailer: document.getElementById('preset_mailer').value,
            host: document.getElementById('preset_host').value.trim(),
            port: document.getElementById('preset_port').value ? parseInt(document.getElementById('preset_port').value,10) : null,
            encryption: document.getElementById('preset_encryption').value || null,
            username: document.getElementById('preset_username').value.trim() || null,
            password: document.getElementById('preset_password').value || null,
            from_address: document.getElementById('preset_from_address').value.trim() || null,
            from_name: document.getElementById('preset_from_name').value.trim() || null
        };
        var isEdit=!!id;
        if(!isEdit && !payload.password) payload.password=null;
        if(isEdit && !payload.password) delete payload.password;
        var url=isEdit ? routes.presetUpdate(id) : routes.presetStore;
        var method=isEdit ? 'PUT' : 'POST';
        fetch(url,{method:method,headers:headers(),body:JSON.stringify(payload)})
        .then(function(r){return r.json().then(function(d){return {ok:r.ok,d:d,status:r.status};});})
        .then(function(res){
            if(res.ok && res.d.success){ showToast(res.d.message,'success'); closePresetModal(); setTimeout(function(){location.reload();},700); }
            else {
                var msg=res.d.message||'Failed to save preset.';
                if(res.d.errors) msg=Object.values(res.d.errors).flat().join(' ');
                showToast(msg,'error');
            }
        }).catch(function(){showToast('Network error.','error');}).finally(function(){setBusy(btn,false);});
    };

    window.applyPreset = function(id,name){
        showConfirm('Use Preset','Apply preset "'+name+'"? This will overwrite the current SMTP settings in .env.',{confirmText:'Apply'})
        .then(function(ok){
            if(!ok) return;
            fetch(routes.presetApply(id),{method:'POST',headers:headers(),body:JSON.stringify({})})
            .then(function(r){return r.json().then(function(d){return {ok:r.ok,d:d};});})
            .then(function(res){
                if(res.ok && res.d.success){ showToast(res.d.message,'success'); closeUsePresetModal(); setTimeout(function(){location.reload();},700); }
                else {
                    var msg=res.d.message||'Failed to apply.';
                    if(res.d.errors) msg=Object.values(res.d.errors).flat().join(' ');
                    showToast(msg,'error');
                }
            }).catch(function(){showToast('Network error.','error');});
        });
    };

    window.deletePreset = function(id,name){
        showDanger('Delete Preset','Delete preset "'+name+'"? This cannot be undone.',{confirmText:'Delete'})
        .then(function(ok){
            if(!ok) return;
            fetch(routes.presetDestroy(id),{method:'DELETE',headers:headers()})
            .then(function(r){return r.json().then(function(d){return {ok:r.ok,d:d};});})
            .then(function(res){
                if(res.ok && res.d.success){ showToast(res.d.message,'success'); var el=document.querySelector('#smtpUsePresetModal [data-preset-id="'+id+'"]'); if(el) el.remove(); if(!document.querySelector('#smtpUsePresetModal [data-preset-id]')) setTimeout(function(){location.reload();},500); }
                else showToast(res.d.message||'Failed to delete.','error');
            }).catch(function(){showToast('Network error.','error');});
        });
    };

    document.addEventListener('keydown', function(e){
        if(e.key !== 'Escape') return;
        var useModal=document.getElementById('smtpUsePresetModal');
        var addModal=document.getElementById('smtpPresetModal');
        if(useModal && useModal.classList.contains('active')){ closeUsePresetModal(); e.stopPropagation(); }
        else if(addModal && addModal.classList.contains('active')){ closePresetModal(); e.stopPropagation(); }
    });
})();
</script>
