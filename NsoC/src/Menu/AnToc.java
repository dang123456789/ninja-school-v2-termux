package Menu;

import History.LichSu;
import Item.ItemName;
import assembly.Item;
import assembly.Option;
import assembly.Player;
import io.Util;
import java.util.ArrayList;
import java.util.List;
import server.Service;
import stream.Server;
import template.ItemTemplate;

/**
 *
 * @author Administrator
 */
public class AnToc {

    public static int[] Luong = new int[]{100, 500, 1000, 1500, 2000, 2500, 3000, 3500, 4000, 4500, 5000, 5500, 6000, 6500, 7000, 8000}; // lượng
    public static int[] Tile = new int[]{100, 90, 80, 70, 60, 50, 40, 30, 25, 20, 15, 10, 8, 5, 3, 1}; // tỉ lệ
    public static int[] Options = new int[]{95, 96, 97, 81, 87, 82, 83, 94, 92, 84, 86, 79, 80, 98, 58, 67};
    public static int[] param = new int[]{
        Util.nextInt(10, 50),
        Util.nextInt(10, 50),
        Util.nextInt(10, 50),
        Util.nextInt(10, 50),
        Util.nextInt(500, 1000),
        Util.nextInt(500, 1000),
        Util.nextInt(500, 1000),
        Util.nextInt(70, 100), // max 100
        Util.nextInt(10, 20), // max 100
        Util.nextInt(10, 20),
        Util.nextInt(10, 20),
        Util.nextInt(5, 5),
        Util.nextInt(10, 20),
        Util.nextInt(5, 5),
        Util.nextInt(5, 5),
        Util.nextInt(5, 5)};

    public static void MenuAnToc(Player p, byte npcid, byte menuId, byte b3) {
        if (p.c.isNhanban) {
            p.conn.sendMessageLog("Chức năng này không dành cho phân thân");
            return;
        }
        switch (menuId) {
            case 0:
                Item Item = p.c.ItemBody[29];
                if (Item == null || Item.id != 839) {
                    Service.chatNPC(p, (short) 46, "Bạn Phải Đeo Ấn Tộc Lên Người Mới Có Thể Luyện Ấn");
                    return;
                }
                if (Item.upgrade >= 1) {
                    Service.chatNPC(p, (short) 46, "Ấn Tộc Đã Được Thăng Ấn Không Thể Luyện Ấn");
                    return;
                }
                if (p.c.getBagNull() == 0) {
                    Service.chatNPC(p, (short) 46, "Hành Trang Không Đủ Chổ Trống");
                    return;
                }
                if (p.luong < 1000) {
                    Service.chatNPC(p, (short) 46, "Bạn Không Đủ 1000 Lượng Để Luyện Ấn");
                    return;
                }
                Item it = ItemTemplate.itemDefault(ItemName.AN_TOC);
                int a = Util.nextInt(1, 7);
                List<Integer> list = new ArrayList<>();
                for (int i = 0; i < Options.length; i++) {
                    list.add(i);
                }
                while (it.options.size() < a) {
                    int index = Util.nextInt(list.size());
                    int indexOption = list.get(index);
                    list.remove(index);
                    it.options.add(new Option(Options[indexOption], (param[indexOption])));
                }
                it.setLock(true);
                p.c.addItemBag(true, it);
                p.c.removeItemBody((byte) 29);
                LichSu.LichSuLuong(p.c.name, p.luong, p.luong - 1000, " Luyện Ấn ", -1000);
                p.upluongMessage(-1000);
                String b = "";
                if (a <= 7 && a >= 5) {
                    b = "Ngon ! Hi sinh vì Đam Mê thì chưa bao giờ là Ngu";
                } else if (a >= 2 && a <= 4) {
                    b = "May Mắn Cũng Là 1 Loại Thực Lực , Nếu Không Đủ May Mắn Hãy Nạp !";
                } else {
                    b = "Chỉ Số Mạnh Hay Yếu Phụ Thuộc Vào Nhân Phẩm Của Bạn !";
                }
                Service.chatNPC(p, (short) 46, b);
                break;
            case 1:
                Item item = p.c.get().ItemBody[29];
                if (item == null || item.id != 839) {
                    Service.chatNPC(p, (short) 46, "Hãy Mang Ấn Tộc Vào Mới Được Thăng Ấn.");
                    return;
                }
                 if (item.upgrade >= 16) {
                    Service.chatNPC(p, (short) npcid, "Ấn Tộc Đã Đạt Cấp Tối Đa.");
                    return;
                }
                if (p.luong < Luong[item.upgrade]) {
                    Service.chatNPC(p, (short) 46, "Bạn Không Đủ Lượng Để Thăng Ấn");
                    return;
                }
                if (p.c.quantityItemyTotal(ItemName.DA_THANG_AN) < 5 * item.upgrade) {
                    ItemTemplate data = ItemTemplate.ItemTemplateId(ItemName.DA_THANG_AN);
                    p.conn.sendMessageLog("Bạn không đủ " + 5 * item.upgrade + " " + data.name + " Để Thăng Ấn");
                    return;
                }
                ItemTemplate data = ItemTemplate.ItemTemplateId(item.id);
                Service.startYesNoDlg(p, (byte) 29,
                        "Bạn có muốn nâng cấp " + data.name + " cấp " + (item.upgrade + 1) + " Với " + Luong[item.upgrade]
                        + " Lượng và " + (item.upgrade) * 5 + " Đá Thăng Ấn Với tỷ lệ thành công là "
                        + Tile[item.upgrade] + "% không?");
                break;
            case 2:
                Server.manager.sendTB(p,
                        "Hướng dẫn",
                        "- Để Tham Gia Chức Năng Ấn Tộc Con Cần Có Ấn Tộc  \n"
                        + "- Ấn Tộc Sẽ Được Bán Tại Shop Gia Tộc Với Giá 50.000.000 Xu \n"
                        + "- Gia Tộc Đạt Cấp 30 Trở Lên Mới Có Thể Mua Ấn Tộc \n"
                        + "- Để Luyện Ấn Tộc Con Cần Có 1000 Lượng \n"
                        + "- Khi Luyện Ấn Sẽ Nhận Được Random 1 Đến 8 Chỉ Số Ngẫu Nhiên \n"
                        + "- Nếu May Mắn Sẽ Nhận Được Chỉ Số Ngon \n"
                        + "- Để Thăng Ấn Con Cần Có Đá Thăng Ấn \n"
                        + "- Khi Thăng Ấn Con Cần Phải Bỏ Ra 1 Ít Lượng Và 1 Số Đá Thăng Ấn \n"
                        + "- Thăng Ấn Càng Cao Số Lượng Và Đá Thăng Ấn Sẽ Tăng Theo \n"
                        + "- Khi Thăng Ấn Sẽ Được Tăng Chỉ Số Của Ấn \n"
                        + "- Khi Ấn Đạt Cấp 12 Trở Lên Sẽ Có Hào Quang \n"
                );
                break;
        }
    }

    public static void UpgradeAnToc(Player p) {
        Item item = p.c.get().ItemBody[29];
        LichSu.LichSuLuong(p.c.name, p.luong, p.luong - Luong[item.upgrade], " Thăng Ấn ", -Luong[item.upgrade]);
        p.upluongMessage(-Luong[item.upgrade]);
        p.c.removeItemBags(840, 5 * item.upgrade);
        if (Tile[item.upgrade] >= Util.nextInt(150)) {
            for (byte k = 0; k < item.options.size(); ++k) {
                Option option = item.options.get(k);
                if (option.id == 95 || option.id == 96 || option.id == 97 || option.id == 81) {
                    Option ops = option;
                    ops.param += (int) 10;
                }
                if (option.id == 87 || option.id == 82 || option.id == 83) {
                    Option ops = option;
                    ops.param += (int) 625;
                }
                if (option.id == 84 || option.id == 86 || option.id == 80 || option.id == 94 || option.id == 92) {
                    Option ops = option;
                    ops.param += 12;
                }
                if (option.id == 79 || option.id == 98) {
                    Option ops = option;
                    ops.param += (int) 1.5;
                }
                if (option.id == 58 || option.id == 98) {
                    Option ops = option;
                    ops.param += (int) 1;
                }
                if (option.id == 67) {
                    Option ops = option;
                    ops.param += (int) 6;
                }
            }
            item.upgrade = (byte) (item.upgrade + 1);
            item.isLock = true;
            p.c.addItemBag(true, item);
            Service.chatNPC(p, (short) 46, "Thăng Ấn Thành Công");
            p.c.removeItemBody((byte) 29);
        } else {
            Service.chatNPC(p, (short) 46, "Thăng Ấn Thất Bại");
        }
    }
}
