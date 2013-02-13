using System;
using System.Collections.Generic;
using System.Text;

namespace Sante.EMR.SmartClient.Infrastructure.Interface.BusinessEntities
{
    public static class CaseFactory
    {
        public static Case CreateCase()
        {
            Case c = new Case();
            c.Tstamp = DateTime.Now;
            c.Status = Status.Active;
            c.DateOpened = DateTime.Now;
            c.NextTask = "Open";
            c.NextTaskDate = c.DateOpened;
            c.NextTaskObj = new Task();
            c.NextTaskObj.DateCompleted = new DateTime(1900, 1, 1);
            c.NextTaskObj.NextTask = c.NextTask;
            c.NextTaskObj.DateDue = c.DateOpened;
            c.NextTaskObj.SetDate = c.DateOpened; // new DateTime(1900, 1, 1);
            c.NextTaskObj.Completed = 0;


            //c.IntakeForms = new List<IntakeForm>();
            c.Forms = new List<Form>();
            c.SetDate = c.DateOpened;
            c.DateClosed = new DateTime(1900, 1, 1);
            c.StatusDate = c.DateOpened;

            //c.Patient = new Patient();

            return c;

        }
    }
    public interface IFactory<T>
    {

        T Create();

    }



    public class Factory<T> : IFactory<T> where T : new()
    {

        public T Create()
        {

            return new T();

        }


    }
    
}
