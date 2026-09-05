/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Upgrade;

import assembly.Item;
import assembly.Option;
import assembly.Player;
import io.Util;
import java.io.IOException;
import server.Service;

/**
 *
 * @author Administrator
 */
public class UpgradeYoroi {

    public static int[] Percent = new int[]{100, 90, 80, 70, 60, 50, 40, 30, 25, 20, 15, 10, 8, 5, 3, 3};

    public static void MenuUpgradeYoroi(Player p, byte npcid, byte menuId, byte b3) throws IOException {
        switch (menuId) {
            case 0: {
                Item item = p.c.ItemBody[12];
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, "Phân thân không thể sử dụng chức năng này");
                    break;
                }
                if (item == null) {
                    Service.chatNPC(p, (short) npcid, "Hãy mang Yoroi vào mới có thể nâng cấp");
                    break;
                }

                if (item.upgrade == 16) {
                    Service.chatNPC(p, (short) npcid, "Yoroi Đã đạt cấp tối đa");
                    break;
                }
                if (p.c.xu < 10000000) {
                    Service.chatNPC(p, (short) npcid, "Không đủ xu để nâng cấp");
                    return;
                }
//                if (p.c.quantityItemyTotal(222) < 1
//                        || p.c.quantityItemyTotal(223) < 1
//                        || p.c.quantityItemyTotal(224) < 1
//                        || p.c.quantityItemyTotal(225) < 1
//                        || p.c.quantityItemyTotal(226) < 1
//                        || p.c.quantityItemyTotal(227) < 1
//                        || p.c.quantityItemyTotal(228) < 1) {
//                    p.conn.sendMessageLog("Bạn không đủ 7 viên ngọc mỗi loại để nâng cấp");
//                    return;
//                }
                Service.startYesNoDlg(p, (byte) 12, "Bạn có muốn nâng cấp Yoroi Đang sử dụng"
                        + " cấp " + (item.upgrade + 1)
                        + " với 10.000.000 "
                        + " Xu với tỷ lệ thành công là " + UpgradeYoroi.Percent[item.upgrade]
                        + "% không?");
                break;
            }
        }
    }

    public static void UpgradeYoroi(Player p) {
        Item it = p.c.get().ItemBody[12];
        p.c.upxuMessage(-10000000L);
//        p.c.removeItemBags(222, 1);
//        p.c.removeItemBags(223, 1);
//        p.c.removeItemBags(224, 1);
//        p.c.removeItemBags(225, 1);
//        p.c.removeItemBags(226, 1);
//        p.c.removeItemBags(227, 1);
//        p.c.removeItemBags(228, 1);
        if (UpgradeYoroi.Percent[it.getUpgrade()] >= Util.nextInt(1)) {
            for (byte k = 0; k < it.options.size(); ++k) {
                Option option = it.options.get(k);
                if (option.id == 84) {
                    Option ops = option;
                    ops.param += 50;
                }
                if (option.id == 80) {
                    Option ops = option;
                    ops.param += 10;
                }
                if (option.id == 81 || option.id == 79) {
                    Option ops = option;
                    ops.param += (int) 1;
                }
                if (option.id == 82 || option.id == 83) {
                    Option ops = option;
                    ops.param += 200;
                }
            }
            it.setUpgrade(it.getUpgrade() + 1);
            it.setLock(true);
            p.c.addItemBag(true, it);
            Service.chatNPC(p, (short) 45, "Nâng Cấp Thành Công");
            p.c.removeItemBody((byte) 12);
        } else {
            Service.chatNPC(p, (short) 45, " Nâng Cấp Thất Bại !");
        }
    }
}
