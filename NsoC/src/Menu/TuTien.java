/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Menu;

import assembly.Language;
import assembly.Map;
import assembly.Player;
import assembly.TileMap;
import io.Util;
import server.Manager;
import server.Service;
import stream.Server;
import stream.TuTienData;

/**
 *
 * @author Administrator
 */
public class TuTien {

    public static long[] upExpTuTien = new long[]{5000000, 10000000, 20000000, 40000000, 60000000, 80000000, 100000000, 120000000, 140000000, 160000000, 180000000, 200000000, 250000000, 300000000, 350000000, 400000000, 450000000, 500000000, 550000000, 800000000, 900000000, 1000000000 , 1000000000};

    public static void npcTuTien(Player p, byte npcid, byte menuId, byte b3) {
        if (p.c.isNhanban) {
            p.conn.sendMessageLog(Language.NOT_FOR_PHAN_THAN);
            return;
        }
        switch (menuId) {
            case 0: {
                if (p.c.level < 100) {
                    p.conn.sendMessageLog("Trình độ phải đạt level 100 trở lên.");
                    return;
                }
                if (p.c.leveltutien >= 1) {
                    p.conn.sendMessageLog("Con đã theo con đường tu tiên rồi mà.");
                    return;
                }
                if (p.luong < 100000L) {
                    p.conn.sendMessageLog("Để Gia Nhập Tu Tiên Cần Có Đủ 100.000 Lượng.");
                    return;
                }
                p.upluongMessage(-100000L);
                p.c.leveltutien = 1;
                p.conn.sendMessageLog(" Con đã bắt đầu theo học tu tiên ! Hãy Cố Gắng Tu Luyện Con Nhé.");
                break;
            }
            case 1: {
                if (p.c.leveltutien == 0) {
                    p.conn.sendMessageLog("Con Chưa Theo Học Tu Tiên.");
                    return;
                }
                if (TuTienData.tuTien == null) {
                    p.conn.sendMessageLog("Bây giờ chưa phải thời gian để tu luyện.");
                    return;
                }
                if (TuTienData.tuTien != null) {
                    if (TuTienData.tuTien100 && p.c.level < 100) {
                        p.conn.sendMessageLog("Bây giờ là thời gian tu luyện của lv 100 trở lên.");
                        return;
                    }
                }
                if (p.c.getEffId(34) == null) {
                    p.conn.sendMessageLog("Phải sử dụng thí luyện thiếp mới có thể vào.");
                    return;
                }
                Map ma = Manager.getMapid(TuTienData.tuTien.map[0].id);
                for (TileMap area : ma.area) {
                    if (area.numplayers < ma.template.maxplayers) {
                        p.c.tileMap.leave(p);
                        area.EnterMap0(p.c);
                        return;
                    }
                }
                break;
            }
            case 2: {
                if (p.c.leveltutien == 23) {
                    p.conn.sendMessageLog("Đã tu luyện đến cảnh giới cuối cùng " + Server.manager.NameTuTien[p.c.leveltutien]);
                    return;
                }
                if (p.c.leveltutien == 0) {
                    p.conn.sendMessageLog("Con Chưa Theo Học Tu Tiên.");
                    return;
                }
                if (p.luong < 100000) {
                    p.conn.sendMessageLog("Không Đủ Lượng Để Đột Phá.");
                    return;
                }
                if (p.c.exptutien < TuTien.upExpTuTien[p.c.leveltutien - 1] * 1000) {
                    p.conn.sendMessageLog("Không Đủ Kinh Nghiệm Để Đột Phá , Hãy Tu Luyện Tiếp Đi.");
                    return;
                }
                p.upluongMessage(-100000);
                int per = 25 - p.c.leveltutien;
                if (Util.nextInt(1, 50) < per) {
                    p.c.exptutien -= TuTien.upExpTuTien[p.c.leveltutien - 1] * 1000;
                    ++p.c.leveltutien;
                    Service.chatKTG(p.c.name + " Rèn luyện khổ cực. Cuối cùng đã đột phá tư chất Tu Tiên lên Tầng " + Server.manager.NameTuTien[p.c.leveltutien]);
                } else {
                    p.c.exptutien -= TuTien.upExpTuTien[p.c.leveltutien - 1] * 1000;
                    p.conn.sendMessageLog("Đột Phá Thất Bại . Tu Luyện Lại Đi.");
                }
                break;
            }
            case 3: {
                if (p.c.leveltutien == 0) {
                    p.conn.sendMessageLog("Con Chưa Theo Học Tu Tiên.");
                    return;
                }
                String name;
                String options;
                name = Server.manager.NameTuTien[p.c.leveltutien];
                options = Server.manager.OptionsTuTien[p.c.leveltutien];
                if (p.c.leveltutien < 1) {
                    p.conn.sendMessageLog("Con đang là " + name + "\n" + options);
                } else {
                    p.conn.sendMessageLog(
                " Exp Tu Tiên của bạn là : " + p.c.exptutien + "/" + (TuTien.upExpTuTien[p.c.leveltutien - 1] * 1000) + "\n" + " Con đang tu luyện ở tầng : " + name + "\n" + options);
                }
                break;
            }
            case 4: {
                Server.manager.sendTB(p, "Thuộc Tính ",
                        "  - Võ Giả : Tấn Công Quái : + 5000 : HP : + 5000 : Tấn Công : + 2000.\n"
                        + "- Tầm Tiên : Tấn Công Quái : + 7000 : HP : + 7000 : Tấn Công : + 3500.\n"
                        + "- Vấn Đạo : Tấn Công Quái : + 10000 : HP : + 10000 : Tấn Công : + 6000.\n"
                        + "- Luyện Khí : Tấn Công Quái : + 13000 : HP : + 13000 : Tấn Công : + 8500.\n"
                        + "- Trúc Cơ : Tấn Công Quái : + 16000 : HP : + 16000 : Tấn Công : + 10000.\n"
                        + "- Kim Đan : Tấn Công Quái : + 18000 : HP : + 18000 : Tấn Công : + 13500 : Giảm Sát Thương : 800.\n"
                        + "- Nguyên Anh : Tấn Công Quái : + 21000 : HP : + 21000 : Tấn Công : + 17000 : Giảm Sát Thương : 1000.\n"
                        + "- Hóa Thần : Tấn Công Quái : + 25000 : HP : + 25000 : Tấn Công : + 20500 : Giảm Sát Thương : 1200.\n"
                        + "- Phi Thăng Kiếp : Tấn Công Quái : + 30000 : HP : + 30000 : Tấn Công : + 24000 : Giảm Sát Thương : 1400.\n"
                        + "- Luyện Hư : Tấn Công Quái : + 35000 : HP : + 35000 : Tấn Công : + 30000 : Giảm Sát Thương : 1600.\n"
                        + "- Hợp Thể : Tấn Công Quái : + 38000 : HP : + 38000 : Tấn Công : + 32000 : Giảm Sát Thương : 1800 : X2 Chí Mạng : 13%.\n"
                        + "- Đại Thừa : Tấn Công Quái : + 41000 : HP : + 41000 : Tấn Công : + 37000 : Giảm Sát Thương : 1900 : X2 Chí Mạng : 15%.\n"
                        + "- Thăng Tiên Kiếp : Tấn Công Quái : + 45000 : HP : + 45000: Tấn Công : + 39000 : Giảm Sát Thương : 2100 : X2 Chí Mạng : 17%.\n"
                        + "- Chân Tiên : Tấn Công Quái : + 47000 : HP : + 47000 : Tấn Công : + 42000 : Giảm Sát Thương : 2200 : X2 Chí Mạng : 18%.\n"
                        + "- Kim Tiên : Tấn Công Quái : + 50000 : HP : + 50000 : Tấn Công : + 43000 : Giảm Sát Thương : 2300 : X2 Chí Mạng : 20%.\n"
                        + "- Thái Ất : Tấn Công Quái : + 53000 : HP : + 53000 : Tấn Công : + 45000 : Giảm Sát Thương : 2450 : X2 Chí Mạng : 22% : Hút Máu : 7% : Hút Máu Quái : 7% : Hút Máu Người : 5%.\n"
                        + "- Đại La : Tấn Công Quái : + 55000 : HP : + 55000 : Tấn Công : + 48500 : Giảm Sát Thương : 2550 : X2 Chí Mạng : 24% : Hút Máu : 9% : Hút Máu Quái : 9% : Hút Máu Người : 6%.\n"
                        + "- Hợp Đạo kiếp : Tấn Công Quái : + 58000 : HP : + 58000 : Tấn Công : + 50000 : Giảm Sát Thương : 2700 : X2 Chí Mạng : 26% : Hút Máu : 11% : Hút Máu Quái : 11% : Hút Máu Người : 7%.\n"
                        + "- Đạo Tổ Nhân Cảnh : Tấn Công Quái : + 62000 : HP : + 62000 : Tấn Công : + 52500 : Giảm Sát Thương : 2800 : X2 Chí Mạng : 28% : Hút Máu : 13% : Hút Máu Quái : 13% : Hút Máu Người : 9%.\n"
                        + "- Đạo Tổ Địa Cảnh : Tấn Công Quái : + 66000 : HP : + 66000 : Tấn Công : + 54000 : Giảm Sát Thương : 2950 : X2 Chí Mạng : 30% : Hút Máu : 15% : Hút Máu Quái : 15% : Hút Máu Người : 12%.\n"
                        + "- Đạo Tổ Thiên Cảnh : Tấn Công Quái : + 70000 : HP : + 70000 : Tấn Công : + 58500 : Giảm Sát Thương : 3000 : X2 Chí Mạng : 31% : Hút Máu : 17% : Hút Máu Quái : 17% : Hút Máu Người : 14%.\n"
                        + "- Bạch Ngọc Chí Tôn : Tấn Công Quái : + 74000 : HP : + 74000 : Tấn Công : + 62000 : Giảm Sát Thương : 3200 : X2 Chí Mạng : 32% : Hút Máu : 19% : Hút Máu Quái : 17% : Hút Máu Người : 16%.\n"
                        + "- Đại Đế : Tấn Công Quái : + 80000 : HP : + 80000 : Tấn Công : + 70000 : Giảm Sát Thương : 3600 : X2 Chí Mạng : 34% : Hút Máu : 22% : Hút Máu Quái : 20% : Hút Máu Người : 20%.\n"
                );
                return;
            }
            case 5: {
                Server.manager.sendTB(p, "Hướng dẫn", "Để tu tiên con cần đạt level 100 và cần 100.000 lượng\n"
                        + "Để có exp tu tiên phải tu luyện trong map tu tiên\n"
                        + "Vào 18-22h là thời gian cho các tiên nhân tu luyện \n"
                        + "Đủ exp tu tiên có thể đột phá lên cấp cao và nhận nhiều thuộc tính mới \n"
                        + "Để vào được map cần có thí luyện thép.");
                return;
            }
            default: {
                p.conn.sendMessageLog("Chức năng này đang cập nhật!");
                break;
            }
        }
    }
}
