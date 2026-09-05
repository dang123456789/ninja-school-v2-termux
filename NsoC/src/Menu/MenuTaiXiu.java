/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Menu;

import ChanLe.SoiCaucl;
import TaiXiu.SoiCau;
import assembly.Player;
import server.Service;
import stream.Server;

/**
 *
 * @author Administrator
 */
public class MenuTaiXiu {

    public static void MenuTaiXiu(Player p, byte npcid, byte menuId, byte b3) {
        switch (menuId) {
            case 0: {
                if (p.vip < 1) {
                    Service.chatNPC(p, (short) npcid, "Yêu Cầu Vip 1 Trở Lên Mới Có Thể Tham Gia");
                    return;
                }
                switch (b3) {
                    case 0:
                        Server.manager.taixiu[0].InfoTaiXiu(p);
                        break;
                    case 1:
                        Service.sendInputDialog(p, (short) 222, "Nhập tiền cược(chia hết cho 10)");
                        break;
                    case 2:
                        Service.sendInputDialog(p, (short) 223, "Nhập tiền cược(chia hết cho 10)");
                        break;
                    case 3:
                        try {
                            String a = "";
                            int size = SoiCau.soicau.size();
                            int index = size - 1;
                            if (size > 50) {
                                size = 50;
                            }
                            for (int i = 0; i < size; i++) {
                                SoiCau check = SoiCau.soicau.get(index--);
                                a = a + check.ketqua + " - " + check.soramdom + ".\n";
                            }
                            Server.manager.sendTB(p, "Soi Cầu", a);
                        } catch (Exception e) {
                        }
                        break;
                    case 4:
                        Server.manager.sendTB(p, "Hướng dẫn",
                                "Số lượng đặt cược phải là số chia hết cho 10.\n"
                                + "Khi đã đặt cược không được thoát game, nếu thoát game sẽ bị mất số tiền cược và admin sẽ không giải quyết.\n"
                                + "Mỗi phiên cược sẽ là 1 phút, khi thời gian còn 10s sẽ không thể đặt cược.\n"
                                + "Khi đã đặt tài thì không thể đặt xỉu và ngược lại.\n"
                                + "Có thể đặt nhiều lần trong 1 phiên.");
                        break;
                }
                break;
            }
            case 1: {
                if (p.vip < 1) {
                    Service.chatNPC(p, (short) npcid, "Yêu Cầu Vip 1 Trở Lên Mới Có Thể Tham Gia");
                    return;
                }
                switch (b3) {
                    case 0:
                        Server.manager.chanle[0].InfoChanLe(p);
                        break;
                    case 1:
                        Service.sendInputDialog(p, (short) 224, "Nhập tiền cược(chia hết cho 10)");
                        break;
                    case 2:
                        Service.sendInputDialog(p, (short) 225, "Nhập tiền cược(chia hết cho 10)");
                        break;
                    case 3:
                        try {
                            String a = "";
                            int size = SoiCaucl.soicau.size();
                            int index = size - 1;
                            if (size > 50) {
                                size = 50;
                            }
                            for (int i = 0; i < size; i++) {
                                SoiCaucl check = SoiCaucl.soicau.get(index--);
                                a = a + check.ketquacl + " - " + check.soramdomcl + ".\n";
                            }
                            Server.manager.sendTB(p, "Soi Cầu", a);
                        } catch (Exception e) {
                        }
                        break;
                    case 4:
                        Server.manager.sendTB(p, "Hướng dẫn",
                                "Số lượng đặt cược phải là số chia hết cho 10.\n"
                                + "Khi đã đặt cược không được thoát game, nếu thoát game sẽ bị mất số tiền cược và admin sẽ không giải quyết.\n"
                                + "Mỗi phiên cược sẽ là 1 phút, khi thời gian còn 10s sẽ không thể đặt cược.\n"
                                + "Khi đã đặt chăẵ thì không thể đặt lẻ và ngược lại.\n"
                                + "Có thể đặt nhiều lần trong 1 phiên.");
                        break;
                }
                break;
            }
        }
    }
}
