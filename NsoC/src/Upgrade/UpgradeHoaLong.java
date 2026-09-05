package Upgrade;

import assembly.Item;
import assembly.Language;
import assembly.Option;
import assembly.Player;
import io.Message;
import io.Util;
import java.io.IOException;
import server.Manager;
import server.Service;
import template.ItemTemplate;

public class UpgradeHoaLong {

    public static int[] LuongUp = new int[]{10000, 15000, 20000, 25000, 30000, 35000, 40000, 45000, 50000, 55000, 60000, 65000, 70000, 80000, 90000, 100000}; // lượng
    public static int[] TileUp = new int[]{100, 90, 80, 70, 60, 50, 40, 30, 25, 20, 15, 10, 8, 5, 3, 1}; // tỉ lệ

    public static void UpgradeHoaLong(Player p, Item item, int type) throws IOException {
        if (type == 1 && p.luong < UpgradeHoaLong.LuongUp[item.upgrade]) {
            p.conn.sendMessageLog("Không Đủ Lượng");
            return;
        }
        UpgradeHoaLong.UpgradeHoaLongOptions(p, item, type);
        Message m = new Message(13);
        m.writer().writeInt(p.c.xu);//xu
        m.writer().writeInt(p.c.yen);//yen
        m.writer().writeInt(p.luong);//luong
        m.writer().flush();
        p.conn.sendMessage(m);
        m.cleanup();
    }

    public static void UpgradeHoaLongOptions(Player p, Item item, int type) {
        try {
            int upPer = UpgradeHoaLong.TileUp[item.upgrade];
            if (Util.nextInt(250) < upPer) {
                Item itemup = ItemTemplate.itemDefault(p.c.ItemBody[10].id);
                p.c.removeItemBody((byte) 10);
                itemup.quantity = 1;
                itemup.upgrade = (byte) (item.upgrade + 1);
                itemup.isLock = true;
                itemup.options.add(new Option(82, (int) (312.5 * itemup.upgrade)));
                itemup.options.add(new Option(58, (int) (1.25 * itemup.upgrade)));
                itemup.options.add(new Option(79, (int) (1.25 * itemup.upgrade)));
                itemup.options.add(new Option(80, (int) (31.25 * itemup.upgrade)));
                if (itemup.upgrade >= 12) {
                    itemup.options.add(new Option(86, 150));
                }
                if (itemup.upgrade >= 14) {
                    itemup.options.add(new Option(84, 100));
                }
                if (itemup.upgrade == 16) {
                    itemup.options.add(new Option(94, 100));
                }
                p.c.addItemBag(false, itemup);
                Manager.serverChat("Hệ Thống", "Chúc Mừng " + p.c.name + " Đã Nâng Cấp Hỏa Long + " + itemup.upgrade + " Thành Công ");
            } else {
                Service.chatNPC(p, (short) 44, "Nâng cấp thất bại ! Dừng lại là thất bại");
            }
            if (type == 1) {
                p.luong -= UpgradeHoaLong.LuongUp[item.upgrade];
            }
        } catch (Exception e) {
        }
    }
}
