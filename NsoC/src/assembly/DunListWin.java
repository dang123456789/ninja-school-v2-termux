package assembly;

import java.util.HashMap;
/**
 *
 * @author Võ Quang Huy
 */
public class DunListWin {

    private int dunListId;
    public String win;
    public String lose;
    private static int idbase = 0;
    public static HashMap<Integer, DunListWin> dunList = new HashMap();

    public DunListWin(String win, String lose) {
        this.win = win;
        this.lose = lose;
        this.dunListId = idbase++;
        dunList.put(this.dunListId, this);
    }
}
