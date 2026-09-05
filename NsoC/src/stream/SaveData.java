package stream;

import assembly.ClanManager;
import assembly.Player;
import java.text.SimpleDateFormat;
import server.Manager;

public class SaveData implements Runnable {

    public Player player = null;
    private final SimpleDateFormat dateFormatFile = null;

    @Override
    public void run() {
        try {
            Manager.isSaveData = true;
            if (Manager.isSaveData) {
                Player p;
                int i;
                synchronized (Client.gI().conns) {
                    for (i = 0; i < Client.gI().conns.size(); i++) {
                        if (Client.gI().conns.get(i) != null && Client.gI().conns.get(i).player != null) {
                            p = Client.gI().conns.get(i).player;
                            p.flush();
                            if (p.c != null) {
                                p.c.flush();
                                if (p.c.clone != null) {
                                    p.c.clone.flush();
                                }
                            }
                        }
                    }
                }
                synchronized (ClanManager.entrys) {
                    for (i = 0; i < ClanManager.entrys.size(); i++) {
                        if (ClanManager.entrys.get(i) != null) {
                            ClanManager.entrys.get(i).flush();
                        }
                    }
                }
                Manager.isSaveData = false;
            }
            System.out.println("...CapNhatDuLieuNguoiChoi...");
            if (player != null) {
                player.conn.sendMessageLog("Lưu Dữ Liệu Data Thành Công ....");
            }
            return;
        } catch (Exception e) {
            Manager.isSaveData = false;
            return;
        }
    }
}
