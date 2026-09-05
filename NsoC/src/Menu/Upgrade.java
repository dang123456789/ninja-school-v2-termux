/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package Menu;

import Upgrade.UpgradeNhanThuatGiaToc;
import Upgrade.UpgradeHoaLong;
import assembly.Item;
import assembly.Language;
import assembly.Player;
import java.io.IOException;
import server.GameSrc;
import server.Service;
import template.ItemTemplate;

/**
 *
 * @author Administrator
 */
public class Upgrade {

    public static void Upgrade(Player p, byte npcid, byte menuId, byte b3) throws IOException {
        switch (menuId) {
            case 0: {
                ItemTemplate data;
                Item it = p.c.get().ItemBody[13];
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (it == null) {
                    Service.chatNPC(p, (short) npcid, "Hãy Mặc Nhẫn Thuật Gia Tộc Vào Đi Con.");
                    return;
                }
                if (it.id != 427 || it.isExpires) { // ô của mặt nạ tb2
                    Service.chatNPC(p, (short) npcid, "Chỉ hỗ trợ cho Nhẫn Thuật Gia Tộc Vĩnh Viễn.");
                    return;
                }
                if (it.upgrade >= 16) {
                    Service.chatNPC(p, (short) npcid, " Đã đạt cấp độ tối đa ");
                    return;
                }
                data = ItemTemplate.ItemTemplateId(it.id);
                Service.startYesNoDlg(p, (byte) 13, "Bạn có muốn nâng cấp " + data.name + " cấp " + (it.upgrade + 1) + " Với " + UpgradeNhanThuatGiaToc.Luong[it.upgrade]
                        + " lượng tỷ lệ thành công là "
                        + UpgradeNhanThuatGiaToc.Tile[it.upgrade] + "% không?");
                break;
            }
            case 1: {
                ItemTemplate data;
                Item it = p.c.get().ItemBody[10];
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (it == null) {
                    Service.chatNPC(p, (short) npcid, "Hãy Mặc Hỏa Long Vào Đi Con.");
                    return;
                }
                if (it.id != 583 || it.isExpires) { // ô của mặt nạ tb2
                    Service.chatNPC(p, (short) npcid, "Chỉ hỗ trợ cho Hỏa Long Vĩnh Viễn.");
                    return;
                }
                if (it.upgrade >= 16) {
                    Service.chatNPC(p, (short) npcid, " Đã đạt cấp độ tối đa ");
                    return;
                }
                data = ItemTemplate.ItemTemplateId(it.id);
                Service.startYesNoDlg(p, (byte) 10, "Bạn có muốn nâng cấp " + data.name + " cấp " + (it.upgrade + 1) + " Với " + UpgradeHoaLong.LuongUp[it.upgrade]
                        + " lượng tỷ lệ thành công là "
                        + UpgradeHoaLong.TileUp[it.upgrade] + "% không?");
                break;
            }
            case 2: {
                Service.chatNPC(p, (short) npcid, "Đang Cập Nhật.");
                return;
            }
            case 3: {
                ItemTemplate data;
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.c.ItemBody[27] == null) { // ô của mặt nạ tb2
                    Service.chatNPC(p, (short) npcid, "Hãy đeo Mặt Nạ Angel vào người trước rồi nâng cấp nhé.");
                    return;
                }
                if (p.c.ItemBody[27].id != 816) { // ô của mặt nạ tb2
                    Service.chatNPC(p, (short) npcid, "Hãy đeo Mặt Nạ Angel vào người trước rồi nâng cấp nhé.");
                    return;
                }
                if (p.c.ItemBody[27].upgrade >= 16) {
                    Service.chatNPC(p, (short) npcid, " Đã đạt cấp độ tối đa ");
                    return;
                }
                data = ItemTemplate.ItemTemplateId(p.c.ItemBody[27].id);
                Service.startYesNoDlg(p, (byte) 27, "Bạn có muốn nâng cấp " + data.name + " cấp " + (p.c.ItemBody[27].upgrade + 1) + " với " + GameSrc.daup[p.c.ItemBody[27].upgrade] + " Đá Tiến Hoá Cải Trang và " + GameSrc.luongup[p.c.ItemBody[27].upgrade] + " lượng tỷ lệ thành công là " + GameSrc.tileup[p.c.ItemBody[27].upgrade] + "% không?");
                break;
            }
            case 4: {
                ItemTemplate data;
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.c.ItemBody[11] == null) {
                    Service.chatNPC(p, (short) npcid, "Hãy đeo cải trang Tôn Ngộ Không vào người trước rồi nâng cấp nhé.");
                    return;
                }
                if (p.c.ItemBody[11].id != 834) {
                    Service.chatNPC(p, (short) npcid, "Hãy đeo cải trang Tôn Ngộ Không vào người trước rồi nâng cấp nhé.");
                    return;
                }
                if (p.c.ItemBody[11].upgrade >= 16) {
                    Service.chatNPC(p, (short) npcid, " đã đạt cấp tối đa ");
                    return;
                }
                if (p.c.ItemBody[11].upgrade >= 16) {
                    Service.chatNPC(p, (short) npcid, " đã đạt cấp tối đa ");
                    return;
                }
                data = ItemTemplate.ItemTemplateId(p.c.ItemBody[11].id);
                Service.startYesNoDlg(p, (byte) 11, "Bạn có muốn nâng cấp cải trang " + data.name
                        + " cấp " + (p.c.ItemBody[11].upgrade + 1) + " với "
                        + GameSrc.daup[p.c.ItemBody[11].upgrade] + " Đá Tiến Hoá Cải Trang và "
                        + GameSrc.luongup[p.c.ItemBody[11].upgrade] + " lượng tỷ lệ thành công là "
                        + GameSrc.tileup[p.c.ItemBody[11].upgrade] + "% không?");
                break;
            }
            case 5: {
                ItemTemplate data;
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (p.c.ItemBody[11] == null) {
                    Service.chatNPC(p, (short) npcid, "Hãy đeo cải trang Mỵ Nương vào người trước rồi nâng cấp nhé.");
                    return;
                }
                if (p.c.ItemBody[11].id != 833) {
                    Service.chatNPC(p, (short) npcid, "Hãy đeo cải trang Mỵ Nương vào người trước rồi nâng cấp nhé.");
                    return;
                }
                if (p.c.ItemBody[11].upgrade >= 16) {
                    Service.chatNPC(p, (short) npcid, " đã đạt cấp tối đa ");
                    return;
                }
                data = ItemTemplate.ItemTemplateId(p.c.ItemBody[11].id);
                Service.startYesNoDlg(p, (byte) 11_1, "Bạn có muốn nâng cấp cải trang " + data.name + " cấp " + (p.c.ItemBody[11].upgrade + 1) + " với " + GameSrc.daup[p.c.ItemBody[11].upgrade] + " Đá Tiến Hoá Cải Trang và " + GameSrc.luongup[p.c.ItemBody[11].upgrade] + " lượng tỷ lệ thành công là " + GameSrc.tileup[p.c.ItemBody[11].upgrade] + "% không?");
                break;
            }
            case 6: {
                ItemTemplate data;
                int i;
                Item it = p.c.get().ItemBody[12];
                if (p.c.isNhanban) {
                    Service.chatNPC(p, (short) npcid, Language.NOT_FOR_PHAN_THAN);
                    return;
                }
                if (it == null) {
                    Service.chatNPC(p, (short) npcid, "Hãy mặc Yoroi vào người trước.");
                    return;
                }
                for (i = 0; i < it.options.size(); ++i) {
                    if (it.options.get(i).id == 85 && it.options.get(i).param < 9) {
                        Service.chatNPC(p, (short) npcid, "Yêu cầu Yoroi phải đạt tinh luyện 9 mới có thể nâng cấp.");
                        return;
                    }
                }
                if (it.upgrade >= 16) {
                    Service.chatNPC(p, (short) npcid, "  Yoroi đã đạt cấp tối đa ");
                    return;
                }
                data = ItemTemplate.ItemTemplateId(p.c.ItemBody[12].id);
                Service.startYesNoDlg(p, (byte) 12, "Bạn có muốn nâng cấp " + data.name
                        + " cấp " + (p.c.ItemBody[12].upgrade + 1)
                        + " với " + GameSrc.daup[p.c.ItemBody[12].upgrade]
                        + " Mảnh Yoroi và " + GameSrc.luongup[p.c.ItemBody[12].upgrade]
                        + " lượng tỷ lệ thành công là " + GameSrc.tileup[p.c.ItemBody[12].upgrade]
                        + "% không?");
                break;
            }
        }
    }
}
