package stream;

import assembly.ClanManager;
import assembly.Map;
import io.Message;
import io.Util;
import java.io.IOException;
import server.Manager;

import java.time.Instant;
import java.util.Calendar;
import java.util.Date;
import server.Service;
import thiendiabang.ThienDiaBangManager;

public class RunTimeServer extends Thread {

    // BOSS VDMQ
    private static final int[] hoursRefreshBossVDMQ = new int[]{7, 12, 17, 22};
    private static final boolean[] isRefreshBossVDMQ = new boolean[]{false, false, false, false,};
    private static final short[] mapBossVDMQ = new short[]{141, 142, 143};
    // BOSS MAP THƯỜNG

//    private static final int[] hourRefreshBossEvent = new int[]{12, 19, 21, 23};
//    private static final boolean[] isRefreshBossEvent = new boolean[]{false, false, false, false};
//    private static final short[] MapBossEventRandom = new short[]{5, 26, 71, 61, 49};
    private static final int[] hoursRefreshBoss = new int[]{9, 11, 13, 15, 17, 19, 21, 23};
    private static final boolean[] isRefreshBoss = new boolean[]{false, false, false, false, false, false, false, false};
    public static final short[] mapBoss45 = new short[]{14, 15, 16, 34, 35, 52};
    private static final short[] mapBoss55 = new short[]{44, 67};
    private static final short[] mapBoss65 = new short[]{24, 41, 45, 59};
    private static final short[] mapBoss75 = new short[]{18, 36, 54};
     //BOSS MAP LANG CO
    private static final int[] hoursRefreshBossLC = new int[]{12, 21};
    private static final boolean[] isRefreshBossLC = new boolean[]{false, false};
    private static final short[] mapBossLC = new short[]{134, 135, 136, 137};
    // BOSS MAP MỚI   
    private static final int[] timebossmapmoi = new int[]{8, 13, 18, 23};
    private static final boolean[] resetbossmapmoi = new boolean[]{false, false, false, false};
    private static final short[] mapbossmapmoi = new short[]{161};
    // BOSS THẾ GIỚI
    private static final int[] timebossthegioi = new int[]{20, 22};
    private static final boolean[] resetbossthegioi = new boolean[]{false, false};
    private static final short[] mapbossthegioi = new short[]{23};
    // BOSS SỰ KIỆN MỚI
    //
    private static final int[] timebosstruyenthuyet = new int[]{20, 6, 13};
    private static final boolean[] resetbosstruyenthuyet = new boolean[]{false, false, false};
    private static final short[] mapbosstruyenthuyet = new short[]{112};
    public String[] Tips = new String[]{
        "Mua xu lượng trang bị vip liên hệ zalo cho ADMIN ",
        "Em có muốn làm Mặt Trời duy nhất của anh không?\n" +
"Em có biết điều gì tuyệt vời nhất không? Đó là chữ đầu tiên của câu này đó!\n" +
"Trái Đất thì quay quanh Mặt Trời. Còn em thì quay mãi ở trong tâm trí anh!\n" +
"Mãi yêu mình EM.",
        "Trăng kia ai vẽ mà tròn?.",
        " Lòng anh ai trộm mà hoài nhớ thương?."
       };

    @Override
    public void run() {
        try {
            ClanManager clan;
            int i;
            Calendar rightNow;
            int hour;
            int min;
            int sec;
            int j;
            byte k;
            Map map;
            while (Server.running) {
                synchronized (ClanManager.entrys) {
                    for (i = ClanManager.entrys.size() - 1; i >= 0; --i) {
                        if (ClanManager.entrys.get(i) != null) {
                            clan = ClanManager.entrys.get(i);
                            if (!Util.isSameWeek(Date.from(Instant.now()), Util.getDate(clan.week))) {
                                clan.payfeesClan();
                            }
                        }
                    }
                } // TDB 1 Tuần Reset 1 Lần
                synchronized (ThienDiaBangManager.thienDiaBangManager) {
                    if (ThienDiaBangManager.thienDiaBangManager[0] != null) {
                        if (Util.compare_Week(Date.from(Instant.now()), Util.getDate(ThienDiaBangManager.thienDiaBangManager[0].getWeek()))) {
                            ThienDiaBangManager.register = false;
                            ThienDiaBangManager.resetThienDiaBang();
                        }
                    }
                }
                rightNow = Calendar.getInstance();
                hour = rightNow.get(11);
                min = rightNow.get(12);
                sec = rightNow.get(13);
                if (TuTienData.tuTien == null && (hour >= 18 && hour < 22)) {
                    TuTienData.start = true;
                    TuTienData.tuTien100 = true;
                    TuTienData.tuTien = new TuTienData();
                    TuTienData.finish = false;
                    System.err.println("Open Tiên Cảnh");
                    Manager.serverChat("Server", "Tiên cảnh đã mở hãy tận dụng thời gian luyện tập tăng tiến sức mạnh.");
                }

                if (TuTienData.tuTien != null && (hour == 22 && min == 0 && sec == 0) && TuTienData.start) {
                    Manager.serverChat("Server", "Tiên cảnh đã đóng cửa hãy quay lại vào ngày mai.");
                    TuTienData.tuTien.finish();
                    System.err.println("Close Tiên Cảnh");
                }
                if (hour % 24 == 0 && min == 0 && sec == 0) {
                    if (ChienTruong.chienTruong != null) {
                        ChienTruong.chienTruong.finish();
                    }
                    ChienTruong.chienTruong30 = false;
                    ChienTruong.chienTruong50 = false;
                    ChienTruong.finish = false;
                    ChienTruong.start = false;
                    ChienTruong.pointHacGia = 0;
                    ChienTruong.pointBachGia = 0;
                    ChienTruong.pheWin = -1;
                    ChienTruong.bxhCT.clear();
                    ChienTruong.chienTruong = null;
                }
                
                if (ChienTruong.chienTruong != null) {
                    if (ChienTruong.bxhCT.size() > 0) {
                        ChienTruong.bxhCT = ChienTruong.sortBXH(ChienTruong.bxhCT);
                        Service.updateCT();
                    }
                }
                if (hour == 16 && min == 00 && sec == 0) {
                    if (ChienTruong.chienTruong != null) {
                        ChienTruong.chienTruong.finish();
                    }
                    if (ChienTruong.chienTruong == null) {
                        Manager.serverChat("Server", "Chiến trường lv30 đã mở báo danh, hãy nhanh chân đến báo danh chuẩn bị chiến đấu.");
                        ChienTruong.chienTruong30 = true;
                        ChienTruong.chienTruong50 = false;
                        ChienTruong.chienTruong = new ChienTruong();
                        ChienTruong.finish = false;
                        ChienTruong.start = false;
                        ChienTruong.pointHacGia = 0;
                        ChienTruong.pointBachGia = 0;
                        ChienTruong.pheWin = -1;
                        ChienTruong.bxhCT.clear();
                    }
                }
                if (ChienTruong.chienTruong != null && hour == 16 && min == 30 && sec == 0) {
                    ChienTruong.start = true;
                }
                if (ChienTruong.chienTruong != null && hour == 17 && min == 30 && sec == 0 && ChienTruong.start) {
                    ChienTruong.chienTruong.finish();
                }
                if (hour == 19 && min == 0 && sec == 0) {
                    if (ChienTruong.chienTruong != null) {
                        ChienTruong.chienTruong.finish();
                    }
                    if (ChienTruong.chienTruong == null) {
                        Manager.serverChat("Server", "Chiến trường lv50 đã mở báo danh, hãy nhanh chân đến báo danh chuẩn bị chiến đấu.");
                        ChienTruong.chienTruong50 = true;
                        ChienTruong.chienTruong30 = false;
                        ChienTruong.chienTruong = new ChienTruong();
                        ChienTruong.finish = false;
                        ChienTruong.start = false;
                        ChienTruong.pointHacGia = 0;
                        ChienTruong.pointBachGia = 0;
                        ChienTruong.pheWin = -1;
                        ChienTruong.bxhCT.clear();
                    }
                }
                if (ChienTruong.chienTruong != null && hour == 19 && min == 30 && sec == 0) {
                    ChienTruong.start = true;
                }
                if (ChienTruong.chienTruong != null && hour == 20 && min == 30 && sec == 0 && ChienTruong.start) {
                    ChienTruong.chienTruong.finish();
                }
                // Tài Xỉu Lượng
                if ((sec % 1 == 0 || sec == 0)) {
                    try {
                        if ((Server.manager.taixiu[0]).totaltai > 1 || (Server.manager.taixiu[0]).totalxiu > 1) {
                            Server.manager.taixiu[0].Start();
                        } else {
                            Server.manager.taixiu[0].Wait();
                        }
                    } catch (Exception e) {
                    }
                }
                // Chẵn Lẻ Xu
                if ((sec % 1 == 0 || sec == 0)) {
                    try {
                        if ((Server.manager.chanle[0]).totalchan > 1 || (Server.manager.chanle[0]).totalle > 1) {
                            Server.manager.chanle[0].Start();
                        } else {
                            Server.manager.chanle[0].Wait();
                        }
                    } catch (Exception e) {
                    }
                }
                if ((min == 58 || min == 30) && sec == 0) {
                    Manager.serverChat("Thông Báo", "Máy Chủ Đang Cập Nhật Dữ Liệu ... !");
                    SaveData saveData = new SaveData();
                    Thread t1 = new Thread(saveData);
                    t1.start();
                    if (!Manager.isSaveData) {
                        t1 = null;
                        saveData = null;
                    }
                }
                // tips
                if ((min % 10 == 0) && sec == 0) {
                    String str = Tips[Util.nextInt(0, 3)];
                    Manager.serverChat("Tips ", "" + str);
                      ThienDiaBangManager.thienDiaBangManager[0].flush();
                    ThienDiaBangManager.thienDiaBangManager[1].flush();
                    System.out.println("Luu TDB...");
                }
                if (sec % 30 == 0) {
                    Message m = null;
                    try {
                        m = new Message(38);
                        m.writer().writeShort(51);//thay id npc
                        m.writer().writeUTF("Lưu Ý : Khi Đã Đặt Cược Vui Lòng Không Thoát Game !"); // thay nội dung chat
                        m.writer().flush();
                        Client.gI().NinjaMessage(m);
                    } catch (IOException e) {
                    }
                }
                for (j = 0; j < RunTimeServer.timebosstruyenthuyet.length; ++j) {
                    if (RunTimeServer.timebosstruyenthuyet[j] == hour) {
                        if (!RunTimeServer.resetbosstruyenthuyet[j]) {
                            String textchat = "Boss Truyền Thuyết Đã Xuất Hiện Tại :";
                            for (k = 0; k < RunTimeServer.mapbosstruyenthuyet.length; ++k) {
                                map = Manager.getMapid(RunTimeServer.mapbosstruyenthuyet[k]);
                                if (map != null) {
                                    map.refreshBoss(Util.nextInt(0, 10));
                                    if (k == 0) {
                                        textchat = textchat + " " + map.template.name;
                                    } else {
                                        textchat = textchat + ", " + map.template.name;
                                    }
                                    RunTimeServer.resetbosstruyenthuyet[j] = true;
                                }
                            }
                            Manager.chatKTG(textchat);
                        }
                    } else {
                        RunTimeServer.resetbosstruyenthuyet[j] = false;
                    }
                }
                for (j = 0; j < RunTimeServer.timebossthegioi.length; ++j) {
                    if (RunTimeServer.timebossthegioi[j] == hour) {
                        if (!RunTimeServer.resetbossthegioi[j]) {
                            String textchat = "Boss Thế Giới Đã Xuất Hiện Tại :";
                            for (k = 0; k < RunTimeServer.mapbossthegioi.length; ++k) {
                                map = Manager.getMapid(RunTimeServer.mapbossthegioi[k]);
                                if (map != null) {
                                    map.refreshBoss(Util.nextInt(15, 28));
                                    if (k == 0) {
                                        textchat = textchat + " " + map.template.name;
                                    } else {
                                        textchat = textchat + ", " + map.template.name;
                                    }
                                    RunTimeServer.resetbossthegioi[j] = true;
                                }
                            }
                            Manager.chatKTG(textchat);
                        }
                    } else {
                        RunTimeServer.resetbossthegioi[j] = false;
                    }
                }
                for (j = 0; j < RunTimeServer.hoursRefreshBossVDMQ.length; ++j) {
                    if (RunTimeServer.hoursRefreshBossVDMQ[j] == hour) {
                        if (!RunTimeServer.isRefreshBossVDMQ[j]) {
                            String textchat = "BOSS Vùng đất ma quỷ đã xuất hiện tại:";
                            for (k = 0; k < RunTimeServer.mapBossVDMQ.length; ++k) {
                                map = Manager.getMapid(RunTimeServer.mapBossVDMQ[k]);
                                if (map != null) {
                                    map.refreshBoss(Util.nextInt(15, 28));
                                    if (k == 0) {
                                        textchat = textchat + " " + map.template.name;
                                    } else {
                                        textchat = textchat + ", " + map.template.name;
                                    }
                                    RunTimeServer.isRefreshBossVDMQ[j] = true;
                                }
                            }
                            Manager.chatKTG(textchat);
                        }
                    } else {
                        RunTimeServer.isRefreshBossVDMQ[j] = false;
                    }
                }
                for (j = 0; j < RunTimeServer.hoursRefreshBossLC.length; ++j) {
                    if (RunTimeServer.hoursRefreshBossLC[j] == hour) {
                        if (!RunTimeServer.isRefreshBossLC[j]) {
                            String textchat = "BOSS Làng Cổ đã xuất hiện tại:";
                            for (k = 0; k < RunTimeServer.mapBossLC.length; ++k) {
                                map = Manager.getMapid(RunTimeServer.mapBossLC[k]);
                                if (map != null) {
                                    map.refreshBoss(1);
                                    if (k == 0) {
                                        textchat = textchat + " " + map.template.name;
                                    } else {
                                        textchat = textchat + ", " + map.template.name;
                                    }
                                    RunTimeServer.isRefreshBossLC[j] = true;
                                }
                            }
                            Manager.chatKTG(textchat);
                        }
                    } else {
                        RunTimeServer.isRefreshBossLC[j] = false;
                    }
                }
                for (j = 0; j < RunTimeServer.timebossmapmoi.length; ++j) {
                    if (RunTimeServer.timebossmapmoi[j] == hour) {
                        if (!RunTimeServer.resetbossmapmoi[j]) {
                            String textchat = "Mỹ Hầu Vương Đã Xuất Hiện Tại:";
                            for (k = 0; k < RunTimeServer.mapbossmapmoi.length; ++k) {
                                map = Manager.getMapid(RunTimeServer.mapbossmapmoi[k]);
                                if (map != null) {
                                    map.refreshBoss(0);
                                    if (k == 0) {
                                        textchat = textchat + " " + map.template.name;
                                    } else {
                                        textchat = textchat + ", " + map.template.name;
                                    }
                                    RunTimeServer.resetbossmapmoi[j] = true;
                                }
                            }
                            Manager.chatKTG(textchat);
                        }
                    } else {
                        RunTimeServer.resetbossmapmoi[j] = false;
                    }
                }
                for (j = 0; j < RunTimeServer.hoursRefreshBoss.length; ++j) {
                    if (RunTimeServer.hoursRefreshBoss[j] == hour) {
                        if (!RunTimeServer.isRefreshBoss[j]) {
                            String textchat = "Thần thú đã xuất hiện tại:";
                            for (k = 0; k < Util.nextInt(1, 2); ++k) {
                                map = Manager.getMapid(RunTimeServer.mapBoss75[Util.nextInt(RunTimeServer.mapBoss75.length)]);
                                if (map != null) {
                                    map.refreshBoss(Util.nextInt(15, 28));
                                    textchat = textchat + " " + map.template.name;
                                    RunTimeServer.isRefreshBoss[j] = true;
                                }
                            }
                            for (k = 0; k < Util.nextInt(1, 2); ++k) {
                                map = Manager.getMapid(RunTimeServer.mapBoss65[Util.nextInt(RunTimeServer.mapBoss65.length)]);
                                if (map != null) {
                                    map.refreshBoss(Util.nextInt(15, 28));
                                    textchat = textchat + ", " + map.template.name;
                                    RunTimeServer.isRefreshBoss[j] = true;
                                }
                            }
                            for (k = 0; k < Util.nextInt(1, 2); ++k) {
                                map = Manager.getMapid(RunTimeServer.mapBoss55[Util.nextInt(RunTimeServer.mapBoss55.length)]);
                                if (map != null) {
                                    map.refreshBoss(Util.nextInt(15, 28));
                                    textchat = textchat + ", " + map.template.name;
                                    RunTimeServer.isRefreshBoss[j] = true;
                                }
                            }
                            for (k = 0; k < Util.nextInt(1, 2); ++k) {
                                map = Manager.getMapid(RunTimeServer.mapBoss45[Util.nextInt(RunTimeServer.mapBoss45.length)]);
                                if (map != null) {
                                    map.refreshBoss(Util.nextInt(15, 28));
                                    textchat = textchat + ", " + map.template.name;
                                    RunTimeServer.isRefreshBoss[j] = true;
                                }
                            }
                            Manager.chatKTG(textchat);
                        }
                    } else {
                        RunTimeServer.isRefreshBoss[j] = false;
                    }
                }
                Thread.sleep(1000L);
            }
            return;
        } catch (InterruptedException e) {
        }
    }

}
