use tauri::Manager;

#[tauri::command]
fn greet(name: &str) -> String {
    format!("Hello, {}! You've been greeted from Rust!", name)
}

#[cfg_attr(mobile, tauri::mobile_entry_point)]
pub fn run() {
    tauri::Builder::default()
        .setup(|app| {
            let hwid = machine_uid::get().unwrap_or("UNKNOWN".to_string());
            let secret = "SELA_SECURE_HANDSHAKE_2026";
            let url = format!("http://sela.local/terminal/login?hwid={}&secret={}", hwid, secret);
            
            if let Some(window) = app.get_webview_window("main") {
                let script = format!("window.location.replace('{}')", url);
                window.eval(&script).unwrap();
            }
            Ok(())
        })
        .plugin(tauri_plugin_opener::init())
        .invoke_handler(tauri::generate_handler![greet])
        .run(tauri::generate_context!())
        .expect("error while running tauri application");
}
